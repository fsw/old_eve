<?php
/**
 * Module.
 * 
 * @package Framework
 * @author fsw
 */

abstract class Module
{
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
		$config = Site::model('configs')->getByField('key', static::getModuleCode());
		if (empty($key))
		{
			if (empty($config['value']))
			{
				return array();
			}
			return $config['value'];
		}
		if (!empty($config['value'][$key]))
		{
			return $config['value'][$key];
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
