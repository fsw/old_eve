<?php

class Entity
{

	private $id = null;

	private static function getFields();

	private static function showCreate()
	{
	
	}

	private static function xxx()
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

	public function __set()
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
