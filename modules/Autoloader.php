<?php
/**
 *
 * @author fsw
 *
 */

class Autoloader
{
	private static $project;
	
	public static function init()
	{
		spl_autoload_register(array('Autoloader', 'autoload'));
	}

	public static function setProject($project)
	{
		static::$project = $project;
	}
	
	public static function getProject()
	{
		return static::$project;
	}

	public static function getFileName($className)
	{
		$path = explode('\\', $className);
		if (current($path) === static::$project)
		{
			return 'projects' . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $path) . '.php';
		}
		else
		{
			return 'modules' . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $path) . '.php';
		}
		
	}
	
	public static function classExists($className)
	{
		return file_exists(static::getFileName($className));
	}
	
	public static function autoload($className)
	{
		echo static::getFileName($className);
		return require_once(static::getFileName($className));
	}
}
