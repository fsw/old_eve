<?php
/**
 *
 * @author fsw
 *
 */

class Autoloader
{
	private static $folders = array('model', 'lib');
	private static $modules = array();
	private static $projectRoot = '';

	public static function init()
	{
		spl_autoload_register(array('Autoloader', 'autoload'));
	}

	public static function setProjectRoot($root)
	{
		static::$projectRoot = $root;
		static::$modules = Project::getModules();
	}
	
	public static function getProjectRoot()
	{
		return static::$projectRoot;
	}
	
	public static function getClassPath($class)
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
		
	}

	public static function getSearchPaths()
	{
		if (CADO_DEV)
		{
			$paths = array(static::$projectRoot);
			foreach (Project::getModules() as $module)
			{
				$paths[] = 'modules' . DIRECTORY_SEPARATOR . $module;
			}
			$paths[] = 'core';
		}
		else
		{
			$paths[] = '';
		}
		return $paths;
	}
	
	public static function findFile($path)
	{
		foreach (self::getSearchPaths() as $search)
		{
			$file = $search . DIRECTORY_SEPARATOR . $path;
			if (Fs::isFile($file))
			{
				return $file;
			}
		}
		return false;
	}
	
	public static function getFileName($className)
	{
		$path = explode('\\', $className);
		$subpath = explode('_', array_pop($path));
		//example_Test -> example/Test.php
		$subpath1 = implode(DIRECTORY_SEPARATOR, $subpath);
		//example_Test -> example/test/Test.php
		$base = array_pop($subpath);
		$subpath[] = lcfirst($base);
		$subpath[] = $base;
		$subpath2 = implode(DIRECTORY_SEPARATOR, $subpath);
		if (count($path))
		{
			$paths[] = 'modules' . DIRECTORY_SEPARATOR . strtolower($path[0]);
		}
		else
		{
			$paths = array('core', static::$projectRoot);
		}
		foreach ($paths as $path)
		{
			foreach (static::$folders as $folder)
			{
				$allPaths[] = array($path, $folder, $subpath1);
				$allPaths[] = array($path, $folder, $subpath2);
			}
			$allPaths[] = array($path, $subpath1);
			$allPaths[] = array($path, $subpath2);
		}
		foreach ($allPaths as $path)
		{
			if (file_exists($file = implode(DIRECTORY_SEPARATOR, $path) . '.php'))
			{
				return $file;
			}
		}
		throw new Exception('class ' . $className . ' not found');
	}

	public static function autoload($className)
	{
		require_once(static::getFileName($className));
	}
	
	public static function load($fileName)
	{
		require_once(CADO_SRC . $fileName);
	}
	
	public static function requireVendor($path)
	{
		require_once('core' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . $path);
	}
}
