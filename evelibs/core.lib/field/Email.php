<?php

class field_Email extends field_Text
{
	public function validate($data)
	{
		if (!filter_var($data, FILTER_VALIDATE_EMAIL))
		{
			return 'Email "' . $data . '" not valid';
		}
		return true;
	}
	
	public static function obfuscate($val)
	{
		return substr($val, 0, 1) . '...' . substr($val, strpos($val, '@'));
	}
}
