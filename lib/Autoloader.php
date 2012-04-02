<?php
namespace Cado;

spl_autoload_register(array('Cado\Autoloader', 'autoload'));

function rnull(){ return null; };

class Autoloader 
{
    public static function autoload($className)
    {
	  var_dump($className);
	  
	  list($module, $type, $className) = explode('\\', $className . '\\');
	  $className or $className = $type and $type = 'Lib';
	  $module = ($module == 'Cado') ? '..' : (file_exists($module = '../modules/' . strtolower($module)) ? $module : '../project');
	  $type = array_search($type, array('lib' => 'Lib', 'actions' => 'Action', 'entities' => 'Entity', 'models' => 'Model'));
	  var_dump($module, $type, $className);
	  require_once($module . '/' . $type . '/' . implode('/', explode('_', $className)) . '.php');

	  //$type = $className ? $type : 'Lib' . rnull($className = $type);
	  /*if(count($path) != 2)
	  {
		throw new Exception('cant find class ' . $className);
	  }
	  $module = array_shift($path);
	  $type = count($path) == 1 ? 'Lib' : array_shift($path);
	  $className = end($path);
	  
	  if ($module == 'Cado')
	  {
		$filePath = '../';
	  }
	  elseif( file_exists('../modules/' . strtolower($module) . '/') )
	  {
		$filePath = '../modules/' . strtolower($module) . '/';
	  }
	  else
	  {
		$filePath = '../project/';
	  }
	  
	  if ($type == 'Lib')
	  {
		$filePath .= 'lib/';
	  }
	  elseif ($type == 'Action')
	  {
		$filePath .= 'actions/';
	  }
	  elseif ($type == 'Entity')
	  {
		$filePath .= 'entities/';
	  }
	  elseif ($type == 'Model')
	  {
		$filePath .= 'models/';
	  }
	  require_once($filePath . implode('/', explode('_', $className)) . '.php');
	  */
	}
}
 
