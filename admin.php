#!/usr/bin/php
<?php

define('CADO_DEV', true);
require_once('cadolibs/Cado.php');
Cado::init();
array_shift($argv);

function printUsageAndExit()
{
  echo 'usage:' . NL;
  echo 'admin.php install <site>' . NL;
  echo 'admin.php uninstall <site>' . NL;
  echo 'admin.php push <remote>' . NL;
  exit;
}

switch(array_shift($argv))
{
  case 'install':
	break;
  case 'push':
	$remote = array_shift($argv);
	if (!Fs::isFile('_remotes/' . $remote . '.php'))
	{
		die('no such remote' . NL);
	}
	require('_remotes/' . $remote . '.php');
	switch ($config['PROTO'])
	{
	  case 'ssh':
		//echo 'building temp webroots' . NL;
		//system('mkdir _remotes/' . $remote . '_webroots');
		
		$toCopy = 'cadolibs framework modules'; //webroots
		$apacheCfg = '';
		foreach ($config['SITES'] as $key=>$domains)
		{
		  $toCopy .= ' sites/' . $key;
		  //system('mkdir _remotes/' . $remote . '_webroots/' . $key);
		  //system('chmod a+rwx _remotes/' . $remote . '_webroots/' . $key);
		  //system('mkdir _remotes/' . $remote . '_webroots/' . $key . '/uploads');
		  //system('chmod a+rwx _remotes/' . $remote . '_webroots/' . $key . '/uploads');
		  $templateData = array(
			'code' => $key,
			'domains' => $domains,
			'root' => $config['ROOT'],
		  	'dev' => $config['DEV'],
			'db_dsn' => $config['DB_DSN'],
			'db_user' => $config['DB_USER'],
			'db_pass' => $config['DB_PASS'],
			'file_cache' => $config['FILE_CACHE'],
		  );
		  $apacheCfg .= new Template('templates/apache.cfg', $templateData);
		  //file_put_contents('_remotes/' . $remote . '_webroots/' . $key . '/index.php', new Template('templates/index.php', $templateData));
		  //file_put_contents('_remotes/' . $remote . '_webroots/' . $key . '/.htaccess', new Template('templates/htaccess', $templateData));
		}
		//file_put_contents('_remotes/' . $remote . '_webroots/apache.cfg', $apacheCfg);
		
		//echo 'Switching webroots' . NL;
		//system('mv webroots webroots_local');
		//system('mv _remotes/' . $remote . '_webroots webroots');
		
		$cmd = 'rsync -avzR --progress -e ssh ' . $toCopy . ' ' . $config['HOST'] . ':' . $config['ROOT'] . '/';
		echo $cmd . NL;
		system($cmd);
		
		//echo 'removing temp webroots' . NL;
		//system('rm -r webroots');
		//system('mv webroots_local webroots');
		break;
	  default:
		die('unknown proto' . NL);
		break;
	}
	break;
  default:
	printUsageAndExit();
	break;

}



/*
if ($argc < 2)
{
	echo 'usage: ' . PHP_EOL;
	echo './build.sh PROJECT SERVER' . PHP_EOL;
	exit;
}

$PROJECT = $argv[1];
$SERVER = empty($argv[2]) ? 'prod' : $argv[2];
$configFilePath = 'projects/' . $PROJECT . '/config-' . $SERVER . '.php';

if (!Fs::exists($configFilePath))
{
	echo 'No configuration for project "' . $PROJECT . '" and server "' . $SERVER . '" found' . PHP_EOL;
	echo 'file not found: ' . $configFilePath . PHP_EOL;
	exit;
}

$config = array();
require_once($configFilePath);
Autoloader::setProjectRoot('projects/' . $PROJECT);

$outDir = $PROJECT . '-build';

if (Fs::exists($outDir))
{
	Fs::remove($outDir, true);
}
Fs::mkdir($outDir);
Fs::mkdir($outDir . '/web');
Fs::mkdir($outDir . '/src');

function preProcess($path)
{
	$extension = substr($path, strpos($path, '.') + 1);
	switch ($extension)
	{
		case 'js':
		case 'css':
			return false;
		default:
			return true;
	}
}

Fs::copyr('projects/' . $PROJECT, $outDir . '/src', 'preProcess');
Fs::copyr('core', $outDir . '/src', 'preProcess');

foreach (Project::getModules() as $module)
{
	Fs::copyr('modules/' . $module, $outDir . '/src/' . $module, 'preProcess');
}

Fs::copyr('web', $outDir . '/web', 'preProcess');

$index = '<?php ' . PHP_EOL;
$index .= 'define(\'CADO_DEV\', \'false\');' . PHP_EOL;
foreach (array('CADO_DB_DSN', 'CADO_DB_USER', 'CADO_DB_PASS', 'CADO_FILE_CACHE') as $key)
{
	$index .= 'define(\'' . $key . '\', \'' . $config[$key] . '\');' . PHP_EOL;
}

$index .= 'chdir(\'..\');' . PHP_EOL;
$index .= 'require_once(\'core/init.php\');' . PHP_EOL;
$index .= 'Routing::processRequest();' . PHP_EOL;

Fs::write($outDir . '/web/index.php', $index);

*/
