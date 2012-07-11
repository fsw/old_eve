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
			static::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		elseif (static::$connection == null)
		{
			$config = array('dsn' => 'mysql:host=localhost;dbname=cado', 'user' => 'cado', 'pass' => 'cado');
			static::$connection = new PDO($config['dsn'], $config['user'], $config['pass']);
			static::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return static::$connection;
	}
	
	public static function getPrefix()
	{
		return 'cado';
	}
	
	public static function fetchAll($sql)
	{
		$sth = static::getConnection()->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function fetchOne($sql)
	{
		$sth = static::getConnection()->prepare($sql);
		$sth->execute();
		return $sth->fetchColumn();
	}

	public static function fetchRow($sql)
	{
		$sth = static::getConnection()->prepare($sql);
		$sth->execute();
		return $sth->fetch(PDO::FETCH_ASSOC);
	}

	public static function fetchCol($sql)
	{
		$sth = static::getConnection()->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_COLUMN);
	}

	public static function query($sql, $bind = array())
	{
		var_dump($sql);
		$q = static::getConnection(true)->prepare($sql);
		$q->execute($bind);
	}

	public static function insert($table, $data)
	{
		$q = 'INSERT INTO `' . $table . '` SET ';
		$keys = array_keys($data);
		foreach($keys as &$key)
		{
			$key = $key .'=?';
		}
		$q .= implode(',', $keys);
		static::query($q, array_values($data));
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
