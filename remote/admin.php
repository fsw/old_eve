#!/usr/bin/php
<?php

//force command line access
if (php_sapi_name() != 'cli' || !empty($_SERVER['REMOTE_ADDR']))
{
	die ('only command line access allowed');
}

//discard script name from argv
array_shift($argv);

//init evelibs
require_once('evelibs/Eve.php');
Eve::init(['remote']);

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

function writeIfDiffers($path, $contents)
{
	$current = null;
	if (Fs::exists($path))
	{
		$current = Fs::read($path);
	}
	if ($current != $contents)
	{
		Fs::write($path, $contents);
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
		check(Fs::isFile('servers/' . $remote . '.php'), 'cant find remote ' . $remote);
		require 'servers/' . $remote . '.php';
		
		check(in_array($server['proto'], array('ssh', 'dev')), 'unknown proto');
		
		
		$commands = array();
		$toPush = array();
		if ($server['proto'] == 'ssh')
		{
			$toPush[0] = array('from' => '.', 'pattern'=>'evelibs', 'to' => $server['srcRoot']);
		}
		$apacheCfg = '';
		
		if ($server['proto'] == 'dev')
		{
			$server['srcRoot'] = dirname(dirname(__FILE__));
			$server['sites'] = array();
			
			$hostsLine = '127.0.0.1';
			foreach (Fs::listDirs('sites', true, true) as $path)
			{
				if (Fs::isFile($path . '/config.php'))
				{
					$site = substr($path, strlen('sites/'));
					Fs::mkdir($server['webDirs'] . $site);
					Fs::mkdir($server['cacheDirs'] . $site);
					system('chmod a+rwx ' . $server['cacheDirs'] . $site);
					Fs::mkdir($server['dataDirs'] . $site);
					system('chmod a+rwx ' . $server['dataDirs'] . $site);
					$server['sites'][$site] = array(
							'domains' => array(implode('.', array_reverse(explode('/', $site))) . '.' . $server['domain']),
							'webDir' => $server['webDirs'] . $site,
							'cacheDir' => $server['cacheDirs'] . $site,
							'dataDir' => $server['dataDirs'] . $site
					);
					$hostsLine .= ' ' . current($server['sites'][$site]['domains']);
					$hostsLine .= ' testsub.' . current($server['sites'][$site]['domains']);
				}
			}
			echo NL . '### Praparing local development environment ###' . NL;
			echo '  Please put following line to your /etc/hosts' . NL;
			echo '  ' . $hostsLine . NL . NL . NL;
		}

		//preparing tmp webroots
		Fs::mkdir('servers/' . $remote . '_webroots');
		foreach ($server['sites'] as $siteCode => $siteConfig)
		{
			if (empty($siteConfig['domain']))
			{
				$siteConfig['domain'] = current($siteConfig['domains']);
			}
			check(Fs::isDir('sites/' . $siteCode), 'Site ' . $siteCode . ' not found');
			
			include 'sites/' . $siteCode . '/config.php';
			foreach ($config['site']['libs'] as $lib)
			{
				$siteConfig['libs'][] = 'evelibs/' . $lib . '.lib';
			}
			$siteConfig['libs'][] = 'sites/' . $siteCode;
			//var_dump($siteConfig);
			//'libs' => array('libs/eve.lib', 'libs/cms.lib', ),
				
			if ($server['proto'] == 'ssh')
			{
				$toPush[0]['pattern'] .= ' sites/' . $siteCode;
			}
			Fs::mkdir('servers/' . $remote . '_webroots/' . $siteCode);
			Fs::mkdir('servers/' . $remote . '_webroots/' . $siteCode . '/cache');
			system('chmod a+rwx servers/' . $remote . '_webroots/' . $siteCode . '/cache');
			
			include 'servers/' . $remote . '.config.php';
			$serverConfig = $config;
			
			$templateData = array(
					'siteCode' => $siteCode,
					'siteConfig' => $siteConfig,
					'devAgent' => $serverConfig['dev']['agent'],
					'dev' => false,
					'srcRoot' => $server['srcRoot'],
			);
			
			writeIfDiffers(
					'servers/' . $remote . '_webroots/' . $siteCode . '/index.php',
					new Template('templates/index.php', $templateData)
			);
			writeIfDiffers(
					'servers/' . $remote . '_webroots/' . $siteCode . '/.htaccess',
					new Template('templates/htaccess', $templateData)
			);
			writeIfDiffers(
					'servers/' . $remote . '_webroots/' . $siteCode . '/maintenance.php',
					new Template('templates/maintenance.php', $templateData)
			);
			include 'servers/' . $remote . '.config.php';
			$config = array_merge($config, array('site' => $siteConfig));
			
			$config['eve']['promotetime'] = time();
			$config['eve']['promoteid'] = uniqid();
			
			writeIfDiffers(
					'servers/' . $remote . '_webroots/' . $siteCode . '/config.php',
					'<?php' . NL . '$config=' . var_export($config, true) . ';' . NL
			);
			
			$apacheCfg .= new Template('templates/apache.cfg', $templateData);
			
			$templateData['dev'] = true;
			writeIfDiffers(
					'servers/' . $remote . '_webroots/' . $siteCode . '/index.dev.php',
					new Template('templates/index.php', $templateData)
			);
			
			$toPush[] = array('from' => 'servers/' . $remote . '_webroots/' . $siteCode, 'pattern'=>'*', 'to' => $siteConfig['webDir']);
			
			//$commands[] = 'php ' . $siteConfig['webDir'] . '/index.dev.php tasks db check';
			//$commands[] = 'php ' . $siteConfig['webDir'] . '/index.dev.php tasks cache clear';
			
			$wget = 'wget -q -O - --cookies=off --user-agent="' . $serverConfig['dev']['agent'] . '" ';
			$commands[] = $wget . 'http://' . $siteConfig['domain'] . '/dev/clearcache.html';
			$commands[] = $wget . '--header "Cookie: use_cache=true" http://' . $siteConfig['domain'] . '/dev/testsuite.html';
			$commands[] = $wget . '--header "Cookie: use_cache=true" http://' . $siteConfig['domain'] . '/dev/warmup.html';
				
			/*if (!empty($serverConfig['superUser']) && !empty($serverConfig['superPass']))
			{
				$commands[] = 'php ' . $siteConfig['webDir'] . '/index.dev.php api call users register '. $config['superUser'] . ' ' . $config['superPass'];
			}*/
		}
		if (!empty($server['apacheCfg']))
		{
			Fs::write('servers/' . $remote . '_webroots/apache.cfg', $apacheCfg);
			$toPush[] = array('from' => 'servers/' . $remote . '_webroots/', 'file'=>'apache.cfg', 'to' => $server['apacheCfg']);
		}

		$current = getcwd();
		foreach ($toPush as $push)
		{
			chdir($push['from']);
			echo 'changed dir to ' . getcwd() . NL;
			switch ($server['proto'])
			{
				case 'ssh':
					if (empty($push['pattern']))
					{
						$cmd = 'rsync -avz --progress -e ssh ' . $push['file'] . ' ' . $server['host'] . ':' . $push['to'];
					}
					elseif ($push['pattern'] === '*')
					{
						$cmd = 'rsync -avzR --progress -e ssh . ' . $server['host'] . ':' . $push['to'];
					}
					else
					{
						$cmd = 'rsync -avzR --progress --delete -e ssh ' . $push['pattern'] . ' ' . $server['host'] . ':' . $push['to'] . '/';
					}
					echo $cmd . NL;
					system($cmd);
					break;
				case 'dev':
					if (empty($push['pattern']))
					{
						$cmd = 'cp -p ' . $push['file'] . ' ' . $push['to'];
					}
					elseif ($push['pattern'] === '*')
					{
						$cmd = 'cp -rp . ' . $push['to'];
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
			switch ($server['proto'])
			{
				case 'dev':
					echo $cmd . NL;
					system($cmd);
					break;
				case 'ssh':
					echo $cmd . NL;
					system('ssh ' . $server['host'] . ' ' . $cmd);
					break;
			}
		}
		echo 'CONFIGURED' . NL;
		break;
	default:
		printUsageAndExit();
		break;
}
