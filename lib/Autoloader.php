<?php

spl_autoload_register(array('Autoloader', 'autoload'));

class Autoloader 
{
    public static function autoload($className)
    {
	  $fileName = implode('/', explode('_', $className)) . '.php';
	  require_once($fileName);
	}
}
 
