<?php

class Config implements iConfig
{

	public static function getProjectCode()
	{
		return 'dev';
	}

	public static function getDatabaseConnection()
	{
		return array(
			'dsn' => 'mysql:host=localhost;dbname=cado',
			'user' => 'cado',
			'pass' => 'cado'
		);
	}

	public static function getMasterDatabaseConnection()
	{
		return static::getDatabaseConnection();
	}

	public static function getSlaveDatabaseConnection()
	{
		return static::getDatabaseConnection();
	}

	public static function getFileCachePath()
	{
		return 'cache';
	}

	public static function getModules()
	{
		return array('User');
	}
}
