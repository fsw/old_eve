<?php
/**
 *
 * @author fsw
 *
 */
require_once('core/init.php');
//TODO determine project
Autoloader::setProjectRoot('projects/xlms');
$controller = Routing::getController();
$controller::run();

/*
if (($code = Routing::get('projectCode')) && ($root = 'projects' . DIRECTORY_SEPARATOR . $code) && (Dir::exists($root)))
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

if ($request->glancePath() == 'api')
{
	$request->shiftPath();
	Api::run($request);
}
else
{
	Project::run($request);
}
*/

//Tickets::validateStructure();

