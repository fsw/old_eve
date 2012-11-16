<?php
/**
 * Array cache.
 * 
 * @package CadoLibs
 * @author fsw
 */

class cache_Array
{
	private static $cache;
	
	public static function set($key, $value)
	{
		$path = Eve::$fileCache . 'arraycache' . DS . $key . '.php';
		Fs::rwrite($path, '<?php' . NL . '$ret=' . var_export($value, true) . ';' . NL);
	}
	
	public static function get($key)
	{
		Dev::startTimer('arraycache');
		$path = Eve::$fileCache . 'arraycache' . DS . $key . '.php';
		
		if ((CADO_DEV && (empty($_COOKIE['use_cache']) || $_COOKIE['use_cache'] == 'false')))
		{
			$ret = null;
		}
		elseif (!empty(static::$cache[$key]))
		{
			$ret = static::$cache[$key];
		}
		elseif (Fs::exists($path))
		{
			include $path;
			static::$cache[$key] = $ret;
		}
		else
		{
			$ret = null;
		}
		Dev::stopTimer();
		
		return $ret;
	}
	
	public static function del($key)
	{
		$path = Eve::$fileCache . 'arraycache' . DS . $key . '.php';
		Fs::remove($path);
	}
	
}