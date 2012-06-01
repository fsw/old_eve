<?php

class field_Date extends Field
{
	public static function getDefinition()
	{
		return 'TIMESTAMP';
	}

	public static function validate($data)
	{

	}

	public static function getJavaScriptValidator()
	{
	}

}
