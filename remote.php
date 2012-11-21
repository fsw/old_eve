#!/usr/bin/php
<?php

//force command line access
if (php_sapi_name() != 'cli' || !empty($_SERVER['REMOTE_ADDR']))
{
	die ('only command line access allowed');
}

//discard script name from argv
array_shift($argv);

//use cadolibs
require_once('cadolibs/Cado.php');
define('CADO_DEV', true);
Cado::init();
Cado::addRoot('framework');

function printUsageAndExit()
{
	echo 'usage:' . NL;
	echo __FILE__ . ' push <remote>' . NL;
	exit;
}

function check($condition, $message)
{
	if (empty($condition))
	{
		echo $message . NL;
		printUsageAndExit();
	}
}

switch (array_shift($argv))
{
	case 'pushTable':
		//get remote config
		check($remote = array_shift($argv), 'please provide remote code');
		check(Fs::isFile('_remotes/' . $remote . '.php'), 'cant find remote ' . $remote);
		require('_remotes/' . $remote . '.php');
		check(is_array($config), 'malformed remote config');
		check(in_array($config['proto'], array('ssh')), 'bad proto');
		
		$configRemote = $config; 
		require('_remotes/dev.php');
		$configLocal= $config;
		
		$localDb = new Db($configLocal['dbConfig']);
		
		$siteCode = array_shift($argv);
		$table = array_shift($argv);
		
		$local_table_name = $configLocal['dbConfig']['prefix'] . $siteCode . '_' . $table;
		$remote_table_name = $configRemote['dbConfig']['prefix'] . $siteCode . '_' . $table;
		
		$tools = new db_Tools($localDb);
		$dump = $tools->dump(array($local_table_name => $remote_table_name), false, true);
		
		$id = uniqid();
		echo $dump;
		die('TODO');
		//system('ssh ' . $config['host'] . ' ' . $cmd);
		
		break;
	case 'push':
		
		//get remote config
		check($remote = array_shift($argv), 'please provide remote code');
		check(Fs::isFile('_remotes/' . $remote . '.php'), 'cant find remote ' . $remote);
		require('_remotes/' . $remote . '.php');
		check(is_array($config), 'malformed remote config');
		check(in_array($config['proto'], array('ssh', 'dev')), 'unknown proto');
		
		$commands = array();
		$toPush = array();
		if ($config['proto'] == 'ssh')
		{
			$toPush[0] = array('from' => '.', 'pattern'=>'cadolibs framework modules docs', 'to' => $config['srcRoot']);
		}
		$apacheCfg = '';
		
		if ($config['proto'] == 'dev')
		{
			$config['srcRoot'] = dirname(__FILE__);
			$config['sites'] = array();
			$config['sites'] = array();
			
			$hostsLine = '127.0.0.1';
			foreach (Fs::listDirs('sites') as $siteCode)
			{
				Fs::mkdir($config['webroots'] . $siteCode);
				$config['sites'][$siteCode] = array(
						'domains' => array($siteCode . '.' . $config['domain']),
						'webroot' => $config['webroots'] . $siteCode,
				);
				$hostsLine .= ' ' . $siteCode . '.' . $config['domain'];
			}
			echo 'Praparing local development environment' .NL;
			echo 'Please put following line to your /etc/hosts' . NL;
			echo $hostsLine . NL;
		}
		
		//preparing tmp webroots
		Fs::mkdir('_remotes/' . $remote . '_webroots');
		foreach ($config['sites'] as $siteCode => $siteConfig)
		{
			if ($config['proto'] == 'ssh')
			{
				$toPush[0]['pattern'] .= ' sites/' . $siteCode;
			}
			Fs::mkdir('_remotes/' . $remote . '_webroots/' . $siteCode);
			system('chmod a+rwx _remotes/' . $remote . '_webroots/' . $siteCode);
			Fs::mkdir('_remotes/' . $remote . '_webroots/' . $siteCode . '/uploads');
			system('chmod a+rwx _remotes/' . $remote . '_webroots/' . $siteCode . '/uploads');
			
			$templateData = array(
					'siteCode' => $siteCode,
					'siteConfig' => $siteConfig,
					'host' => $remote,
					'devAgent' => $config['devAgent'],
					'devEmail' => @$config['devEmail'],
					'srcRoot' => $config['srcRoot'],
					'dev' => false,
					'dbConfig' => $config['dbConfig'],
					'fileCache' => $config['fileCache'] . $siteCode . '/',
					'pushid' => uniqid(),
			);
			
			Fs::write(
					'_remotes/' . $remote . '_webroots/' . $siteCode . '/index.php',
					new Template('templates/index.php', $templateData)
			);
			Fs::write(
					'_remotes/' . $remote . '_webroots/' . $siteCode . '/.htaccess',
					new Template('templates/htaccess', $templateData)
			);
			Fs::write(
					'_remotes/' . $remote . '_webroots/' . $siteCode . '/maintenance.php',
					new Template('templates/maintenance.php', $templateData)
			);
			
			$apacheCfg .= new Template('templates/apache.cfg', $templateData);
			
			$templateData['dev'] = true;
			Fs::write(
					'_remotes/' . $remote . '_webroots/' . $siteCode . '/index.dev.php',
					new Template('templates/index.php', $templateData)
			);
			
			$toPush[] = array('from' => '_remotes/' . $remote . '_webroots/' . $siteCode, 'pattern'=>'*', 'to' => $siteConfig['webroot']);
			
			$commands[] = 'php ' . $siteConfig['webroot'] . '/index.dev.php checkDb';	
			if (!empty($config['superUser']) && !empty($config['superPass']))
			{
				$commands[] = 'php ' . $siteConfig['webroot'] . '/index.dev.php api call users register '. $config['superUser'] . ' ' . $config['superPass'];
			}
		}
		if (!empty($config['apacheCfg']))
		{
			Fs::write('_remotes/' . $remote . '_webroots/apache.cfg', $apacheCfg);
			$toPush[] = array('from' => '_remotes/' . $remote . '_webroots/', 'file'=>'apache.cfg', 'to' => $config['apacheCfg']);
		}
		
		$current = getcwd();
		foreach ($toPush as $push)
		{
			chdir($push['from']);
			echo 'changed dir to ' . getcwd() . NL;
			switch ($config['proto'])
			{
				case 'ssh':
					if (empty($push['pattern']))
					{
						$cmd = 'rsync -avz --progress -e ssh ' . $push['file'] . ' ' . $config['host'] . ':' . $push['to'];
					}
					elseif ($push['pattern'] === '*')
					{
						$cmd = 'rsync -avzR --progress -e ssh . ' . $config['host'] . ':' . $push['to'];
					}
					else
					{
						$cmd = 'rsync -avzR --progress -e ssh ' . $push['pattern'] . ' ' . $config['host'] . ':' . $push['to'] . '/';
					}
					echo $cmd . NL;
					system($cmd);
					break;
				case 'dev':
					if (empty($push['pattern']))
					{
						$cmd = 'cp ' . $push['file'] . ' ' . $push['to'];
					}
					elseif ($push['pattern'] === '*')
					{
						$cmd = 'cp -r . ' . $push['to'];
					}
					echo $cmd . NL;
					system($cmd);
					break;
			}
			chdir($current);
		}
		echo 'PUSHED' . NL;
		foreach ($commands as $cmd)
		{
			switch ($config['proto'])
			{
				case 'dev':
					echo $cmd . NL;
					system($cmd);
					break;
				case 'ssh':
					echo $cmd . NL;
					system('ssh ' . $config['host'] . ' ' . $cmd);
					break;
			}
		}
		echo 'CONFIGURED' . NL;
		break;
	default:
		printUsageAndExit();
		break;
}
