<?php

abstract class Entity
{

	private $id = null;

	public function getFields()
	{
		return array();
	}

	private static function implode(&$data)
	{

	}

	private static function explode(&$data)
	{

	}



	private static function postSave()
	{

	}

	private static function getTableName()
	{
		return Config::getProjectCode() . '_' . get_called_class();
	}

	public function __construct($mixed)
	{
	  if (is_numeric($mixed))
	  {
		$this->constructFromId($mixed);
	  }
	  elseif (is_array($mixed))
	  {
	  	$this->constructFromData($mixed);
	  }
	}

	private function addField(Field $field)
	{

	}

	public function constructFromId($id)
	{

	}

	public function constructFromData($data)
	{

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
