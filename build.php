#!/usr/bin/php
<?php

require_once('core/init.php');

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
