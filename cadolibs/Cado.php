<?php
/**
 *
 * @author fsw
 *
 */

define('DS', DIRECTORY_SEPARATOR);
define('NL', PHP_EOL);

final class Cado
{
	public static $root = null;
	public static $outputCache = null;
	
	public static $errorHandler = null;
	private static $roots = array('cadolibs');
	
	public static function init()
	{
		if (PHP_SAPI !== 'cli')
		{
			session_start();
		}
		self::$root = dirname(dirname(__FILE__)) . DS;
		self::$outputCache = getcwd() . DS;
		chdir(self::$root);
		/*
		require('classes' . DS . 'cache' . DS . 'Array.php');
		require('classes' . DS . 'dev' . DS . 'Dev.php');
		require('classes' . DS . 'fs' . DS . 'Fs.php');
		if (cache_Array::get('cadoClasses') === null)
		{
			$before = get_declared_classes();
				
			spl_autoload_register(array('Cado', 'autoload'));
			self::includeAll();
			$after = get_declared_classes();
			var_dump(array_diff($after, $before));
			//cache_Array::get('cadoClasses')
		}*/
		spl_autoload_register(array('Cado', 'autoload'));
		self::$errorHandler = new ErrorHandler();
		Dev::startTimer('all');
		//TODO
		if (get_magic_quotes_gpc())
		{
			function stripslashes_gpc(&$value)
			{
				$value = stripslashes($value);
			}
			array_walk_recursive($_GET, 'stripslashes_gpc');
			array_walk_recursive($_POST, 'stripslashes_gpc');
			array_walk_recursive($_COOKIE, 'stripslashes_gpc');
			array_walk_recursive($_REQUEST, 'stripslashes_gpc');
		}
		
	}
	
	public static function handleException(Exception $e)
	{
		self::$errorHandler->handleException($e);
	}
	
	public static function autoload($className)
	{
		if ($className !== 'Dev')
		{
			Dev::startTimer('classloader');
			Dev::logEvent('autoload', $className);
		}
		$file = self::getClassFileName($className);
		if ($file !== null)
		{
			require($file);
		}
		if ($className !== 'Dev')
		{
			Dev::stopTimer();
		}
	}
	
	public static function getRoots()
	{
		return self::$roots;
	}
	
	public static function addRoot($root)
	{
		array_unshift(self::$roots, $root);
	}
	
	public static function shiftRoots()
	{
		array_shift(self::$roots);
	}
	
	public static function getClassFileName($className)
	{
		{
			//self::includeAll();
			
		}
		$path = explode('_', $className);
		$baseName = array_pop($path);
		$path = implode(DS, $path);
		foreach (static::$roots as $root)
		{
			$searchFiles[] = self::$root . $root . DS . 'classes' . DS . (empty($path) ? '' : $path . DS) . $baseName . '.php';
			$searchFiles[] = self::$root . $root . DS . 'classes' . DS . (empty($path) ? '' : $path . DS) . lcfirst($baseName) . DS . $baseName . '.php';
		}
		foreach ($searchFiles as $file)
		{
			if (file_exists($file))
			{
				return $file;
			}
		}
		return null;
	}
	
	public static function classExists($className)
	{
		return self::getClassFileName($className) !== null;
	}
	
	public static function findResource($path)
	{
		Dev::startTimer('resourceloader');
		//just for windows sake
		$path = str_replace('/', DS, $path);
		$ret = null;
		foreach (static::$roots as $root)
		{
			if (file_exists(self::$root . $root . DS . $path))
			{
				$ret = self::$root . $root . DS . $path;
				break;
			}
		}
		Dev::stopTimer();
		return $ret;
	}
	
	/**
	 * this function should never be called in a normal application workflow
	 */
	private static function includeAll()
	{
		$classFiles = array();
		$included = array();
		foreach (static::$roots as $root)
		{
			$files = Fs::listFiles($root . '/classes', true, true);
			foreach ($files as $file)
			{
				if ((strpos($file, '.svn') === false) && empty($included[substr($file, strlen($root))]))
				{	
					$included[substr($file, strlen($root))] = true;
					$classFiles[] = $file;
				}
			}
		}
		foreach ($classFiles as $file)
		{
			require_once($file);
		}
	}
	
	public static function getDescendants($class)
	{
		//TODO arraycache!
		static::includeAll();
		$children  = array();
		foreach (get_declared_classes() as $c){
			if (is_subclass_of($c, $class))
			{
				$r = new ReflectionClass($c);
				if (!$r->isAbstract())
				{
					$children[] = $c;
				}
			}
		}
		return $children;
	}
	/*
	public static function getParents($class)
	{
		$ret = array();
		$reflection = new ReflectionClass($class);
		do {
			$ret[] = $reflection->getName();
			$reflection = $reflection->getParentClass();
		} while( false !== $reflection );
		return $ret;
	}
	
	public static function getNonAbstractChildClasses($parent)
	{
	
	}*/
}
