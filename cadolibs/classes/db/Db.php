<?php
/**
 * @package Libs
 * @author fsw
 * @qualit 90%
 *
 */
class Db
{
	private $config = null;
	private $reader = null;
	private $writer = null;
	private $prefix = '';
	
	/**
	 * Connect only when necessairy so can be constructed at application start
	 * 
	 * @param Array $config database connection parameters
	 * 
	 * Example usage:
	 * @code
	 * new Db(['dsn' => DSN, 'user' => USER, 'pass' => PASS]); //to create single connection
	 * new Db([
	 * 	['dsn' => DSN, 'user' => USER, 'pass' => PASS, 'write' => true], //master params
	 * 	['dsn' => DSN, 'user' => USER, 'pass' => PASS, 'write' => false, weight => 4], // slave 1 params 
	 * 	['dsn' => DSN, 'user' => USER, 'pass' => PASS, 'write' => false, weight => 1], // slave 2 params
	 * 	]);
	 * @endcode
	 */
	public function __construct(Array $config = array())
	{
		//TODO slaves!
		$this->config = $config;
	}
	
	private function getReader()
	{
		if ($this->writer !== null)
		{
			return $this->writer;
		}
		if ($this->reader === null)
		{
			//TODO
			$config = $this->config;
			$this->reader = new PDO($config['dsn'], $config['user'], $config['pass']);
			$this->reader->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return $this->reader;
	}
	
	private function getWriter()
	{
		if ($this->writer === null)
		{
			//TODO
			$config = $this->config;
			$this->writer = new PDO($config['dsn'], $config['user'], $config['pass']);
			$this->writer->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return $this->writer;
	}
	
	public function fetchAll($sql, $bind = array())
	{
		$sth = $this->getReader()->prepare($sql);
		$sth->execute($bind);
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}

	public function fetchOne($sql, $bind = array())
	{
		$sth = $this->getReader()->prepare($sql);
		$sth->execute($bind);
		return $sth->fetchColumn();
	}

	public function fetchRow($sql, $bind = array())
	{
		$sth = $this->getReader()->prepare($sql);
		$sth->execute($bind);
		return $sth->fetch(PDO::FETCH_ASSOC);
	}

	public function fetchCol($sql, $bind = array())
	{
		$sth = $this->getReader()->prepare($sql);
		$sth->execute($bind);
		return $sth->fetchAll(PDO::FETCH_COLUMN);
	}

	public function query($sql, $bind = array())
	{
		$q = $this->getWriter()->prepare($sql);
		$q->execute($bind);
	}

	public static function toSet($data)
	{
		$keys = array_keys($data);
		foreach($keys as &$key)
		{
			$key = '`' . $key . '`=?';
		}
		return implode(',', $keys);
	}
	
	public function insert($table, $data)
	{
		$q = 'INSERT INTO `' . $table . '` SET ';
		$q .= self::toSet($data);
		$this->query($q, array_values($data));
	}
	
	public function update($table, $id, $data)
	{
		$q = 'UPDATE `' . $table . '` SET ';
		$q .= self::toSet($data);
		$q .= ' WHERE id = ' . $id;
		$this->query($q, array_values($data));
	}

	public function delete($table, $id)
	{
		$this->query('DELETE FROM `' . $table . '` WHERE id = ' . $id);
	}
	
	public function getStructure()
	{
		$current = array();
		foreach ($this->fetchCol('SHOW TABLES') as $t)
		{
			$current[$t] = array();
			$create = $this->fetchRow('SHOW CREATE TABLE ' . $t);
			$create = $create['Create Table'];
			$create = substr($create, strpos($create, '(') + 1, strrpos($create, ')') - strpos($create, '(') - 1);
			$create = explode(',' . "\n", $create);
			foreach ($create as $row)
			{
				if (strpos($row, 'KEY') !== false)
				{
					if (strpos($row, 'PRIMARY') !== false)
					{
						$current[$t]['index_primary'] = trim($row);
					}
					else
					{
						$start = strpos($row, '`') + 1;
						$current[$t][substr($row, $start, strpos($row, '`', $start) - $start)] = trim($row);
					}
				}
				else
				{
					$current[$t][substr($row, strpos($row, '`') + 1, strrpos($row, '`') - strpos($row, '`') - 1)] =
					trim(substr($row, strrpos($row, '`') + 1));
				}
			}
		}
		return $current;
	}

}
