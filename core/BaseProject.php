<?php

abstract class BaseProject
{
	public static function run()
	{
	}
	
	public static function getCode()
	{
	}
	
	public static function getModules()
	{
		return array('Api');
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
			$className = $module . '\\Module';
			$model = $className::getModel();
			foreach ($model as &$m)
			{
				$m = $module . '\\' . $m;
			}
			$ret = array_merge($ret, $model);
		}
		return $ret;
	}
	
}
