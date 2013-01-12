<?php
/**
 * This model class represents database table.
 * 
 * @package Core
 * @author fsw
 */

abstract class model_Table extends Model
{
	private static $fields = [];
	private static $indexes = [];
	protected static $crossSite = false;
	
	protected static function initFields()
	{
		return [];
	}
	
	protected static function initIndexes()
	{
		return [];
	}

	public static function getFields()
	{
		if (empty(self::$fields[self::getBaseName()]))
		{
			self::$fields[self::getBaseName()] = static::initFields();
		}
		return self::$fields[self::getBaseName()];
	}
	
	public static function getField($key)
	{
		$fields = self::getFields();
		return $fields[$key];
	}
	
	public static function getIndexes()
	{
		if (empty(self::$indexes[self::getBaseName()]))
		{
			self::$indexes[self::getBaseName()] = static::initIndexes();
		}
		return self::$indexes[self::getBaseName()];
	}
	
	protected static function getTableName()
	{
		$db = Config::get('model', 'db');
		return $db['prefix'] . (static::$crossSite ? '' : Config::get('site', 'code') . '_') . self::getBaseName();
	}
	
	public static function _getDbStructure()
	{
		$ret = array();
		$ret[static::getTableName()] = array();
		foreach (static::getFields() as $key => $field)
		{
			if ($field instanceof field_relation_Many)
			{
				$f = new field_Number();
				$ret[static::getTableName() . '_xref_' . $key] = array(
						static::getBaseName() . '_id' => $f->getDbDefinition(),
						$key . '_id' => $f->getDbDefinition()
				);
			}
			elseif ($field instanceof field_relation_Container)
			{
	
			}
			else
			{
				$definition = $field->getDbDefinition();
				if (is_array($definition))
				{
					foreach ($definition as $subkey => $def)
					{
						$ret[static::getTableName()][$key . '_' . $subkey] = $def;
					}
				}
				else
				{
					$ret[static::getTableName()][$key] = $definition;
				}
			}
		}
		if (!empty(static::$versioned))
		{
			$ret[static::getRevisionsTableName()] = static::getRevisionsTableStructure($ret[static::getTableName()]);
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
	
	
}
