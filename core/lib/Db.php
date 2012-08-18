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
	
	public static function fetchAll($sql, $bind = array())
	{
		$sth = static::getConnection()->prepare($sql);
		$sth->execute($bind);
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function fetchOne($sql, $bind = array())
	{
		$sth = static::getConnection()->prepare($sql);
		$sth->execute($bind);
		return $sth->fetchColumn();
	}

	public static function fetchRow($sql, $bind = array())
	{
		$sth = static::getConnection()->prepare($sql);
		$sth->execute($bind);
		return $sth->fetch(PDO::FETCH_ASSOC);
	}

	public static function fetchCol($sql, $bind = array())
	{
		$sth = static::getConnection()->prepare($sql);
		$sth->execute($bind);
		return $sth->fetchAll(PDO::FETCH_COLUMN);
	}

	public static function query($sql, $bind = array())
	{
		//var_dump($sql);
		$q = static::getConnection(true)->prepare($sql);
		$q->execute($bind);
	}

	public static function toSet($data)
	{
		$keys = array_keys($data);
		foreach($keys as &$key)
		{
			$key = $key .'=?';
		}
		return implode(',', $keys);
	}
	
	public static function insert($table, $data)
	{
		$q = 'INSERT INTO `' . $table . '` SET ';
		$q .= self::toSet($data);
		static::query($q, array_values($data));
	}
	
	public static function update($table, $id, $data)
	{
		$q = 'UPDATE `' . $table . '` SET ';
		$q .= self::toSet($data);
		$q .= ' WHERE id = ' . $id;
		static::query($q, array_values($data));
	}

	public static function delete($table, $id)
	{
		static::query('DELETE FROM `' . $table . '` WHERE id = ' . $id);
	}


}
