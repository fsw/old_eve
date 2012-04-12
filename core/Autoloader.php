<?php
namespace Core;

class Autoloader 
{

	public static function init()
	{
	  spl_autoload_register(array('Core\Autoloader', 'autoload'));
	}
	
	public static function setProjectRoot()
	{
	
	}

    public static function autoload($className)
    {
	  $path = explode('\\', $className);
	  if (count($path))
	  require_once('..' . DIRECTORY_SEPARATOR . 'mods' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php');
	}
}
 
