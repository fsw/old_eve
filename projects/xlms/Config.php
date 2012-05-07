<?php

class Config implements iConfig {

	public static function getProjectCode()
	{
		return 'xlms';
	}

	public static function getMasterDatabaseConnection()
	{
		return static::getDatabaseConnection();
	}

	public static function getSlaveDatabaseConnection()
	{
		return static::getDatabaseConnection();
	}

	public static function getDatabaseConnection()
	{
		return array(
	  'dsn' => 'mysql:host=localhost;dbname=cado',
	  'user' => 'cado',
	  'pass' => 'cado'
	  );
	}

	public static function getModules()
	{
		return array('cado', 'users');
	}

}

