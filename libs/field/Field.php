<?php

abstract class Field
{
	const UNSIGNED = 1;
	const OPTIONAL = 2;
	
	public function getDbDefinition()
	{
		return 'varchar(255) DEFAULT NULL';
	}
	
	public function getLoremIpsum()
	{
		return 'Lorem Ipsum';
	}
	
	public function getHtml($data)
	{
		return $data;
	}

	public function getJsRegexp()
	{
		return '';
	}

	public function serialize($data)
	{
		return $data;
	}

	public function unserialize($data)
	{
		return $data;
	}
	
	public function getFormInput($key, $value)
	{
		return '<input type="text" name="' . $key . '" value="' . $value . '">';
	}

	public function validate($data)
	{
		return true;
	}
}
