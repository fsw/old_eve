<?php


trait userSchema
{
	function getFields()
	{
	}

}

class Users extends Collextion
{
	use userSchema;

}

class User extends Entity
{
	use userSchema;

}


class UserSchema extends Schema
{
	__construct()
	{
		$this->name = new NameField();

	}
}

class Users extends Collextion
{
	f getS()
	{
		return new UserSchema();
	}
}

class User extends Entity
{
	use userSchema;

}


abstract class Collection
{

	public static function getEntitiyClass()
	{
		return new Entity();
	}


	getEntitiyClass()::getFields();


	public static function createEntity($data)
	{
		return new Entity($data);
	}

	protected static function doGetFields() { throw new Exception('is abstact'); }
	public static function getFields()
	{
		return static::doGetFields();
	}

	protected static function doGetFields()
	{
		return call_user_function(static::getEntitiyClass(), 'getFields');
	}

	public static function getAll()
	{
		call_user_func( array(), 6, 2 );
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
		/*
		initStructure
		foreach (static::getFields() as $key => $field)
		{
			$sql[] = '`' . $key . '` ' .  $field::getDefinition();
		}*/
		return 'CREATE TABLE ' . static::getTableName() . ' (' . implode(',', $sql) . ')';
	}

	private static function getTableName()
	{
		return Config::getProjectCode() . '_' . get_called_class();
	}
}
