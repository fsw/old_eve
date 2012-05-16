<?php

abstract class Entity
{
	protected static function getFields() { throw new }

	private $id = null;

	public static function getFields()
	{
		return array();
	}

	public static function implode(&$data)
	{
		return $data;
	}

	public static function explode(&$data)
	{
		return $data;
	}

	public function __set($key, $value)
	{

	}

	public function __get($key)
	{

	}

	public function save()
	{
		if (empty($this->id))
		{
		  $this->id = Db::insert(static::getTableName(), $this->id, $this->data);
		}
		else
		{
		  Db::update(static::getTableName(), $this->id, $this->data);
		}
	}

	public function delete()
	{
		if (!empty($this->id))
		{
		  Db::delete(static::getTableName(), $this->id);
		}
	}
}
