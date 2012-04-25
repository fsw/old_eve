<?php
/**
 *
 * @author fsw
 *
 */

class Autoloader
{
	public static function init()
	{
		spl_autoload_register(array('Autoloader', 'autoload'));
		//TODO file + arraycache
		static::$coreClasses = array('Cache');
		static::$modules = Cache::get(array('autoloader', 'modules'));
	}

	public static function getFileName($className)
	{
		if (in_array($className, static::$coreClasses))
		{
			return 'core' . DIRECTORY_SEPARATOR . $className . '.php';
		}
		$path = explode('\\', $className);
		if (count($path) > 1 && in_array(reset($path), static::$modules))
		{
			array_unshift($path, 'modules');
		}
		else
		{
			array_unshift($path, 'project');
		}
		array_push($path, explode('_', array_pop($path)));
    	return implode(DIRECTORY_SEPARATOR, $path) . '.php';		
	}
	
	public static function classExists($className)
	{
		return file_exists(static::getFileName($className));
	}
	
	public static function autoload($className)
	{
		//echo static::getFileName($className);
		require_once(static::getFileName($className));
	}
}
