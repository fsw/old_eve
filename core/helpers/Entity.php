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
	
	static function getAll()
	{
	
	}
	
	public static function recreateStructure()
	{
		Db::query('DROP TABLE IF EXISTS ' . static::getTableName());
		Db::query(static::showCreate());
	}
		
	public static function validateStructure()
	{
		$current = Db::fetchAll('describe ' . static::getTableName());
		$current[] = static::showCreate();
		/*if (count($current))
		{
		
		}*/
		return $current;
	}
	
	private static function showCreate()
	{
		$sql[] = '`id` INT';
		foreach (static::getFields() as $key => $field)
		{
			$sql[] = '`' . $key . '` ' .  $field::getDefinition();
		}
		return 'CREATE TABLE ' . static::getTableName() . ' (' . implode(',', $sql) . ')';
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
