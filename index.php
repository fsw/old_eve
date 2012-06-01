<?php
/**
 *
 * @author fsw
 *
 */
require_once('core/init.php');

$request = new Request();

if (($code = $request->shiftPath()) && ($root = 'projects' . DIRECTORY_SEPARATOR . $code) && (Dir::exists($root)))
{
	Autoloader::setProjectRoot($root);
}
else
{
	$request->unshiftPath($code);
	Autoloader::setProjectRoot('projects' . DIRECTORY_SEPARATOR . 'dev');
}
if (PHP_SAPI === 'cli')
{
  $request->unshiftPath('api');
}


Project::run($request);

//Tickets::validateStructure();

