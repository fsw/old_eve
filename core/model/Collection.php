<?php

abstract class Collection
{
	private static $CollectionsFields = array();
	
	protected static function getFields()
	{
		return array(
			'id' => new field_Id()
		);
	}
	
  	protected static function getIndexes()
  	{
  		return array(
			'primary' => array('id')
		);
  	}

	public static function fields()
	{
		if (!array_key_exists(get_called_class(), self::$CollectionsFields))
		{
			self::$CollectionsFields[get_called_class()] = static::getFields();
		}
		return self::$CollectionsFields[get_called_class()]; 
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
			elseif ($field instanceof relation_ManyToOne)
			{
				$ret[static::getTableName()][$key . '_id'] = 'int(11) DEFAULT NULL';
			}
			elseif ($field instanceof relation_ManyToMany)
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
	
	public static function add($fields)
	{
		Db::insert(self::getTableName(), $fields);
	}
	
	public static function update($id, $fields)
	{
	
	}

	public static function searchIds($args)
	{
		return array();
	}
	
	public static function search($args)
	{
		$rows = \Db::fetchAll('SELECT * FROM ' . self::getTableName());
		foreach ($rows as &$row)
		{
			static::explode($row);
		}
		return $rows;
	}
	
	public static function getById($id)
	{
		return static::search(array('id' => $id));
	}

	protected static function getBaseName()
	{
		return strtolower(str_replace('\\', '_', get_called_class()));
	}
	
	private static function getTableName()
	{
		return Db::getPrefix() . '_' . Project::getCode() . '_' . self::getBaseName();
	}
	
	protected static function explode(&$row)
	{
		return $row;
	}
}
