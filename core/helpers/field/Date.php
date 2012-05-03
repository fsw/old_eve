<?php

class field_Date extends Field
{
	public static function getDefinition()
	{
		return 'TIMESTAMP';
	}

	private static function validate($data)
	{

	}

	private static function getJavaScriptValidator()
	{
	}

}
