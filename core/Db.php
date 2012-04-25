<?php

class Db
{
	static $connection = null;

	private static function getConnection()
	{
		if (static::$connection == null)
		{
			$config = Config::getDatabaseConnection();
			static::$connection = new PDO($config['dsn'], $config['user'], $config['pass']);
		}
		return static::$connection;
	}

	public static function fetchAll($sql)
	{

	}

	public static function fetchOne($sql)
	{

	}

	public static function fetchRow($sql)
	{

	}

	public static function fetchCol($sql)
	{

	}

	public static function query($sql)
	{
		static::getConnection()->query($sql);
	}

	public static function insert($table, $data)
	{

	}

	public static function update($table, $id, $data)
	{
		static::query('UPDATE `' . $table . '` WHERE id = ' . $id);
	}

	public static function delete($table, $id)
	{
		static::query('DELETE FROM `' . $table . '` WHERE id = ' . $id);
	}


}
