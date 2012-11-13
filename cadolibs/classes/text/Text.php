<?php
/** 
 * Common text processing functions
 * 
 * @package CadoLibs
 * @author fsw
 */

class Text
{
	public static function excerpt($text, $maxLength = 100)
	{
		return mb_strlen($text) > $maxLength ? mb_substr($text, 0, $maxLength) . '...' : $text;
	}

	public static function slug($text)
	{
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
		$clean = preg_replace("/[^a-zA-Z0-9]/", '-', $clean);
		$clean = strtolower(trim($clean, '-'));
		return empty($clean) ? 'slug' : $clean;
	}
}