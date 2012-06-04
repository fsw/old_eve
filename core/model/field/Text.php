<?php

class field_Text extends Field
{
	public static function __construct($minLength, $maxLength)
	{
		$this->minLength = $minLength;
		$this->maxLength = $maxLength;
	}

	public static function getDbDefinition()
	{
		return 'TEXT';
	}

	public static function validate($data)
	{
		return true;
	}

	public static function getJsRegexp()
	{
		return '';
	}

}
