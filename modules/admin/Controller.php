<?php
/**
 * 
 * @author fsw
 *
 */
namespace Cado;
use Autoloader;

class Controller
{
	static function warnAboutOldBrowser()
	{
		
	}
	
	static function controll()
	{
		$request = new Request();
		$actionName = $path = $request->getPath() ? ucfirst(current($path)) : 'Index';
		if (Autoloader::classExists($actionClass = Autoloader::getProject() . '\\Action\\' . $actionName))
		{
			$response = $actionClass::execute($request);
			$response->output();
		}
	}
}

