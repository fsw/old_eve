<?php
/**
 * Array cache.
 * 
 * @package CadoLibs
 * @author fsw
 */

class cache_Array
{
	public static function set()
	{
		$args = func_get_args();
		$value = var_export(array_pop($args), true);
		$path = Eve::$fileCache . 'arraycache' . DS . implode(DS, $args) . '.php';
		Fs::rwrite($path, '<?php' . NL . '$x=' . $value . ';' . NL);
	}
	
	public static function get()
	{
		if ((CADO_DEV && (empty($_COOKIE['use_cache']) || $_COOKIE['use_cache'] == 'false')))
		{
			return null;
		}
		Dev::startTimer('arraycache');
		$args = func_get_args();
		$path = Eve::$fileCache . 'arraycache' . DS . implode(DS, $args) . '.php';
		if (Fs::exists($path))
		{
			include $path;
		}
		else
		{
			$x = null;
		}
		Dev::stopTimer();
		return $x;
	}
	
	public static function del()
	{
		$args = func_get_args();
		$path = Eve::$fileCache . implode(DS, $args) . '.php';
		Fs::remove($path);
	}
	
}