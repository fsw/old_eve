<?php
/**
 *
 * @author fsw
 *
 */

class Autoloader
{
	private static $folders = array('model', 'helpers', 'actions', 'widgets', 'entities', 'lib');
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
	
	public static function getClassTree($root = null)
	{

	}
	
	public static function getNonAbstractChildClasses($parent)
	{
		
	}

	public static function getSerachPaths()
	{
		$paths = array('core');
		foreach (Project::getModules() as $module)
		{
			$paths[] = 'modules' . DIRECTORY_SEPARATOR . $module;
		}
		$paths[] = static::$projectRoot;
		return $paths;
	}
	
	public static function getFileName($className)
	{
		$path = explode('\\', $className);
		$subpath = explode('_', array_pop($path));
		$subpath = implode(DIRECTORY_SEPARATOR, $subpath);

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
				$allPaths[] = array($path, $folder, $subpath);
			}
			$allPaths[] = array($path, $subpath);
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
		require_once($fileName);
	}
}
