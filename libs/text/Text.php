<?php
/**
 * 
 * @author fsw
 *
 */
class Text
{
	public static function excerpt($text, $maxLength = 100)
	{
		return mb_strlen($text) > $maxLength ? mb_substr($text, 0, $maxLength) . '...' : $text;
	}
	
}