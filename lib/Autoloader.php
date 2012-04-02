<?php
namespace Cado;

spl_autoload_register(array('Cado\Autoloader', 'autoload'));

class Autoloader 
{
    public static function autoload($className)
    {
	  $path = explode('\\', $className);
	  $fileName = '';
	  $module = array_shift($path);
	  $type = count($path) == 1 ? 'Lib' : array_pop($namespace);
	  $className = end($path);
	  if ($module == 'Cado')
	  {
		$fileName = '../';
	  }
	  else
	  {
		$fileName = '../modules/' . strtolower($modules) . '/';
	  }

	  if ($type == 'Lib')
	  {
		$fileName .= 'lib/';
	  }
	  elseif ($type == 'Action')
	  {
		$fileName .= 'actions/';
	  }
	  $fileName .= implode('/', explode('_', $className)) . '.php';

	  var_dump($fileName); 
	  require_once($fileName);
	}
}
 
