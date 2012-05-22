<?php

abstract class Entity
{
	static $structure = null;
	static $fields = array();

	protected static function structure()
	{
		throw new Exception( __CLASS__ . 'structure() should be implemented' );
	}

	private static function getStructure()
	{
		if (empty(static::$structure))
		{
			static::$structure = static::structure();
		}
		return static::$structure;
	}

	public static function validate()
	{

	}

	protected static function implode(&$data)
	{
		return $data;
	}

	protected static function explode(&$data)
	{
		return $data;
	}

	public function __set($key, $value)
	{
		$this->fields[$key] = $value;
	}

	public function __get($key)
	{
		return $this->fields[$key];
	}

}
