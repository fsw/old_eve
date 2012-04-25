<?php

class Config implements iConfig
{
	
	public static function getDatabaseConnection()
	{
		return array(
			'dsn' => '',
			'user' => '',
		 	'pass' => '',
		);
	}

	public static function getFileCachePath()
	{
		return 'cache';
	}
}
