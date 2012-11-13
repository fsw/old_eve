<?php
/**
 * Memcached.
 * 
 * @package CadoLibs
 * @author fsw
 */

class cache_Memcached
{
	static $connection = null;
	
	public static function getConnection()
	{
		if ($connection == null)
		{
			$connection = new Memcached();
			$connection->addServer('localhost', 11211);
		}
		return $connection;
	}
	
	public static function set($key, $value)
	{
		self::getConnection()->set($key, $value);
	}
	
	public static function get($key)
	{
		Dev::startTimer('apc');
		Dev::logEvent('apc', $key);
		$ret = apc_fetch($key, $success);
		Dev::stopTimer();
		return $success ? $ret : null;
	}
	
	public static function del($key)
	{
		apc_delete($key);
	}
	
}