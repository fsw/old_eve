<?php
/**
 * Module.
 * 
 * @package Framework
 * @author fsw
 */

abstract class Module
{
	public function getModules()
	{
		return array();
	}
	
	public static function getModuleCode()
	{
		return lcfirst(str_replace('module_', '', get_called_class()));
	}
	
	public static function getConfigFields()
	{
		return array();
	}
	
	public static function getConfig($key = null)
	{
		$configs = cache_Array::get('configs');
		if ($configs === null)
		{
			$configs = array();
			$rows = Site::model('configs')->getAll();
			foreach ($rows as $row)
			{
				$configs[$row['key']] = $row['value'];
			}
			cache_Array::set('configs', $configs);
		}
		
		$config = $configs[static::getModuleCode()];
		if (empty($key))
		{
			return empty($config) ? array() : $config;
		}
		if (!empty($config[$key]))
		{
			return $config[$key];
		}
		return null;
	}
	
	public static function saveConfig($config)
	{
		$current = Site::model('configs')->getByField('key', static::getModuleCode());
		if (empty($current))
		{
			$current = array();
		}
		$current['key'] = static::getModuleCode();
		$current['value'] = $config;
		return Site::model('configs')->save($current);
	}
}
