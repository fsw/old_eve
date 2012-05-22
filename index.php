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
	$request->unshiftPath();
	Autoloader::setProjectRoot('projects' . DIRECTORY_SEPARATOR . 'dev');
}

Project::run($request);
