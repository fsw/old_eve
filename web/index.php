<?php
/**
 *
 * @author fsw
 *
 */

define('CADO_DEV', true);
define('CADO_DB_DSN', 'mysql:host=localhost;dbname=cado');
define('CADO_DB_USER', 'cado');
define('CADO_DB_PASS', 'cado');
define('CADO_FILE_CACHE', '/tmp/cado/');

define('CADO_DOMAIN', 'cado');

chdir('..');
require_once('core/init.php');

if (Request::getProject() && Fs::exists($projectPath = 'projects' . DIRECTORY_SEPARATOR . Request::getProject()))
{
	Autoloader::setProjectRoot($projectPath);
	require_once($projectPath . DIRECTORY_SEPARATOR . 'bootstrap.php');
}
else
{
	$projects = Fs::listDirs('projects');
	echo '<ul>';
	foreach($projects as $project)
	{
		echo '<li><a href="' . Request::href(array($project), array('index')) . '">' . $project . '</a></li>';
	}
	echo '</ul>';
}
