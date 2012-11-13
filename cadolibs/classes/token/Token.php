<?php 
/** 
 * @package CadoLibs
 * @author fsw
 */

class Token
{
	private static $mask = 0x7f2951bc;
	
	public static function intToCode($int)
	{
		//$int = ($int << 4) + 0xf;
		$int = $int ^ self::$mask;
		return base_convert($int, 10, 36);
	}
	
	public static function codeToInt($code)
	{
		$int = base_convert($code, 36, 10);
		return $int ^ self::$mask;
	}
}