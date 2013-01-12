<?php
/**
 * Value.
 * 
 * @package Core
 * @author fsw
 */

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
		return rand(0, 100);
	}

	public function getJsRegexp()
	{
		return '';
	}

	public function toDb($value, $key, $code, &$row)
	{
		return $value;
	}

	public function fromDb($cell, $key, $code, &$row)
	{
		return $cell;
	}
	
	public function getFormInput($key, $value)
	{
		return '<input type="text" name="' . $key . '" value="' . $value . '">';
	}

	public function validate($value)
	{
		return true;
	}
	
	public function fromPost($post)
	{
		return $post;
	}
}
