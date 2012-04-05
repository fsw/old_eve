<?php
namespace Core;

spl_autoload_register(array('Core\Autoloader', 'autoload'));

class Autoloader 
{
    public static function autoload($className)
    {
	  require_once('..' . DIRECTORY_SEPARATOR . 'mods' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php');
	}
}
 
