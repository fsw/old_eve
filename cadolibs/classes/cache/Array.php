<?php
/**
 * 
 * @author fsw
 *
 */
class cache_Array
{
	public static function set()
	{
		$args = func_get_args();
		$value = var_export(array_pop($args), true);
		$path = CADO_FILE_CACHE . implode(DIRECTORY_SEPARATOR, $args) . '.php';
		Fs::write($path, '<?php\n$x=' . $value);
	}
	
	public static function get()
	{
		$args = func_get_args();
		$path = CADO_FILE_CACHE . implode(DIRECTORY_SEPARATOR, $args) . '.php';
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
		$path = CADO_FILE_CACHE . implode(DIRECTORY_SEPARATOR, $args) . '.php';
		Fs::remove($path);
	}
	
}