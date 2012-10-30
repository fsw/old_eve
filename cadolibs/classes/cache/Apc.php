<?php
/**
 * 
 * @author fsw
 *
 */
class cache_Apc
{
	public static function set($key, $value, $ttl = 60)
	{
		apc_store($key, $value, $ttl);
	}
	
	public static function get($key)
	{
		if (CADO_DEV)
		{
			return null;
		}
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