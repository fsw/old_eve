<?php

abstract class Field
{
	public static function getDbDefinition()
	{
		return 'INT';
	}

	public static function getJSValidator()
	{
		return '';
	}
	
	public static function validate($data)
	{
		return true;
	}

	public static function serialize($data)
	{
		return $data;
	}

	public static function unserialize($data)
	{
		return $data;
	}


}
