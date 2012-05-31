<?php

abstract class Collection
{
	protected static function fields()
	{
		return array(
			'id' => new field_Int()
		);
	}

	public static function validateStructure()
	{
		$current = Db::fetchAll('describe ' . static::getTableName());
		if (empty($current))
		{
			echo static::getCreate();
		}
		$valid = static::getCreate();
		var_dump($current, $valid);
		/*if (count($current))
		{

		}*/
		return $current;
	}

	private static function getCreate()
	{
		foreach (static::fields() as $key => $field)
		{
			$sql[] = '`' . $key . '` ' .  $field::getDbDefinition();
		}
		return 'CREATE TABLE ' . static::getTableName() . ' (' . PHP_EOL . implode(',' . PHP_EOL, $sql) . PHP_EOL . ')';
	}

	public static function relationManyToOne(){
		return new field_Int();
	}

	public static function getAll()
	{
		$data = Db::fetchAll('SELECT * FROM ' . static::getTableName());
		foreach ($data as &$row)
		{
			$row = static::getEntity($row);
		}
	}

	public static function getById($id)
	{
		$data = Db::fetchRow('SELECT * FROM ' . static::getTableName());
		foreach ($data as &$row)
		{
			$row = static::getEntity($row);
		}
	}



	public static function recreateStructure()
	{
		Db::query('DROP TABLE IF EXISTS ' . static::getTableName());
		Db::query(static::showCreate());
	}




	private static function getTableName()
	{
		return 'test' . '_' . get_called_class();
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
