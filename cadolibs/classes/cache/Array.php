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
		$path = Eve::$fileCache . implode(DS, $args) . '.php';
		Fs::write($path, '<?php\n$x=' . $value);
	}
	
	public static function get()
	{
		$args = func_get_args();
		$path = Eve::$fileCache . implode(DS, $args) . '.php';
		if (Fs::exists($path))
		{
			include $path;
			return $x;
		}
		else
		{
			return null;
		}
	}
	
	public static function del()
	{
		$args = func_get_args();
		$path = Eve::$fileCache . implode(DS, $args) . '.php';
		Fs::remove($path);
	}
	
}