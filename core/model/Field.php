<?php

abstract class Field
{
	public static function getDbDefinition();

	public static function validate($data);

	public static function getHtml();

	public static function getJsRegexp();

	public static function serialize($data)
	{
		return $data;
	}

	public static function unserialize($data)
	{
		return $data;
	}

}
