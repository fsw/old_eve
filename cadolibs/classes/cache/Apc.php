<?php
/**
 * APC cache.
 * 
 * @package CadoLibs
 * @author fsw
 */

class cache_Apc
{
	public static function set($key, $value, $ttl = 60)
	{
		if (function_exists('apc_store'))
		{
			apc_store($key, $value, $ttl);
		}
	}
	
	public static function get($key)
	{
		if ((CADO_DEV && (empty($_COOKIE['use_cache']) || $_COOKIE['use_cache'] == 'false')) || !function_exists('apc_fetch'))
		{
			return null;
		}
		Dev::startTimer('apc');
		$ret = apc_fetch($key, $success);
		Dev::logEvent('apc', $key, $ret);
		Dev::stopTimer();
		return $success ? $ret : null;
	}
	
	public static function del($key)
	{
		if (function_exists('apc_delete'))
		{
			apc_delete($key);
		}
	}
	
}