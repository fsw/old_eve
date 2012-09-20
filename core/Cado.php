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
	public static $root;
	public static $siteCode = '';
	public static $site = null;
	public static $db = null;
	private static $roots = array('libs', 'framework');
	
	public static function init()
	{
		session_start();
		self::$root = dirname(dirname(__FILE__)) . DS;
		chdir(self::$root);
		spl_autoload_register(array('Cado', 'autoload'));
		new ErrorHandler();
	}
	
	public static function autoload($className)
	{
		$file = self::getClassFileName($className);
		if ($file === null)
		{
			throw new Exception('class ' . $className . ' not found');
		}
		require($file);
	}
	
	public static function addRoot($root)
	{
		array_unshift(self::$roots, $root);
	}
	
	public static function getClassFileName($className)
	{
		$path = explode('_', $className);
		$baseName = array_pop($path);
		$path = implode(DS, $path);
		foreach (static::$roots as $root)
		{
			$searchFiles[] = self::$root . $root . DS . (empty($path) ? '' : $path . DS) . $baseName . '.php';
			$searchFiles[] = self::$root . $root . DS . (empty($path) ? '' : $path . DS) . lcfirst($baseName) . DS . $baseName . '.php';
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
		//just for windows sake
		$path = str_replace('/', DS, $path);
		foreach (static::$roots as $root)
		{
			if (Fs::exists(self::$root . $root . DS . $path))
			{
				return self::$root . $root . DS . $path;
			}
		}
		return null;
	}
	
	/**
	 * this function should never be called in a normal application workflow
	 */
	private static function includeAll()
	{
		$classFiles = array();
		foreach (static::$roots as $root)
		{
			$files = Fs::listFiles($root, true, true);
			foreach ($files as $file)
			{
				$chunks = explode('.', substr($file, strrpos($file, DS) + 1));
				$ommit = DS . 'vendor' . DS;
				if (strrpos($file, $ommit) === false && count($chunks) > 1 && array_pop($chunks) == 'php' && !in_array(array_pop($chunks), array('html', 'css', 'js')))
				{
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
