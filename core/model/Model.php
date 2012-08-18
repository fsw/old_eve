<?php

abstract class Model
{

	private static $ModelsFields = array();

	public static function fields()
	{
		//TODO ???
		$class = get_called_class();
		if (!array_key_exists($class, self::$ModelsFields))
		{
			self::$ModelsFields[$class] = static::getFields();
		}
		return self::$ModelsFields[$class];
	}
	
	public static function getStructure()
	{
		$ret = array();
		$ret[static::getTableName()] = array();
		foreach (static::fields() as $key => $field)
		{
			if ($field instanceof Field)
			{
				$ret[static::getTableName()][$key] = $field->getDbDefinition();
			}
			elseif ($field instanceof relation_One)
			{
				$ret[static::getTableName()][$key . '_id'] = 'int(11) DEFAULT NULL';
			}
			elseif ($field instanceof relation_Many)
			{
				$f = new field_Int();
				$ret[static::getTableName() . '_xref_' . $key] = array(
					static::getBaseName() . '_id' => $f->getDbDefinition(),
					$key . '_id' => $f->getDbDefinition()
				);
			}
		}
		foreach (static::getIndexes() as $key => $field)
		{
			$unique = ($key == 'primary') || array_shift($field); 
			$ret[static::getTableName()]['index_' . $key] = 
				($key == 'primary' ? 'PRIMARY KEY' : (($unique ? 'UNIQUE ' : '') . 'KEY ' . '`index_' . $key . '`'))
				. ' (`' . implode($field , '`,`') . '`)';
		}
		return $ret;
	}
	
	protected static function getBaseName()
	{
		return strtolower(str_replace('\\', '_', get_called_class()));
	}
	
	protected static function getTableName()
	{
		return Db::getPrefix() . '_' . Project::getCode() . '_' . self::getBaseName();
	}
	
	protected static function validate($row)
	{
		$errors = array();
		foreach (static::fields() as $key => $field)
		{
			$error = $field->validate(isset($row[$key]) ? $row[$key] : null);
			if ($error !== true)
			{
				$errors[] = $error;
			}
		}
		return $errors;
	}
	
	protected static function explode(&$row)
	{
		foreach (static::fields() as $key => $field)
		{
			if ($field instanceof Field)
			{
				$row[$key] = $field->unserialize($row[$key]);
			}
			elseif ($field instanceof relation_One)
			{
				$row[$key] = (int)$row[$key . '_id'];
				unset($row[$key . '_id']);
			}
			elseif ($field instanceof relation_Many)
			{
				$row[$key] = array();
			}		
		}
		return $row;
	}
	
	public static function search($where, $bind = array())
	{		
		$rows = \Db::fetchAll('SELECT * FROM ' . self::getTableName() . ' WHERE ' . $where, $bind);
		//SQL_CALC_FOUND_ROWS
		//SELECT FOUND_ROWS();
		foreach ($rows as &$row)
		{
			static::explode($row);
		}
		return $rows;
	}

	public static function getAll()
	{
		return static::search('1');
	}
	
	public static function searchOne($where, $bind = array())
	{
		$ret = static::search($where, $bind);
		return reset($ret);
	}
	
	public static function add($row)
	{
		$errors = static::validate($row);
		foreach (static::fields() as $key => $field)
		{
			if ($field instanceof relation_Many)
			{
				unset($row[$key]);
			}
			elseif ($field instanceof relation_One)
			{
				$ret[$key . '_id'] = (int)$row[$key];
				unset($row[$key]);
			}
		}	
		Db::insert(self::getTableName(), $row);
	}
}