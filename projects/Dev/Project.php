<?php

class Project
{
	public static $codeName = 'dev';

	public static function getDatabaseConnection()
	{
		return array(
	 		'host'	=> 'localhost'
	 		);
	}

	public static function getEnabledModules($className)
	{

	}
}

