<?php

class Db
{
	static $connection = null;
	static $write = false;

	private static function getConnection($write = false)
	{
		if ($write && !static::$write)
		{
			static::$write = true;
			//TODO
			$config = array('dsn' => 'mysql:host=localhost;dbname=cado', 'user' => 'cado', 'pass' => 'cado');
			static::$connection = new PDO($config['dsn'], $config['user'], $config['pass']);
		}
		elseif (static::$connection == null)
		{
			$config = array('dsn' => 'mysql:host=localhost;dbname=cado', 'user' => 'cado', 'pass' => 'cado');
			static::$connection = new PDO($config['dsn'], $config['user'], $config['pass']);
		}
		return static::$connection;
	}

	public static function fetchAll($sql)
	{
		$sth = static::getConnection()->prepare($sql);
		$sth->execute();
		return $sth->fetchAll();
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
