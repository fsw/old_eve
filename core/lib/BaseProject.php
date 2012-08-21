<?php

abstract class BaseProject
{
	public static function run()
	{
	}
	
	public static function getCode()
	{
	}
	
	public static function getName()
	{
		return 'New Project';
	}
	
	public static function getModules()
	{
		return array();
	}
	
	public static function isModuleOn($code)
	{
		return in_array($code, static::getModules());
	}
	
	public static function getModel()
	{
		$ret = array();
		foreach (static::getModules() as $module)
		{
			$className = ucfirst($module) . '\\Module';
			$model = $className::getModel();
			foreach ($model as &$m)
			{
				$m = ucfirst($module) . '\\' . $m;
			}
			$ret = array_merge($ret, $model);
		}
		return $ret;
	}
	
}
