<?php

abstract class Collection extends Model
{
			
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
	
	public static function save($row)
	{
		if (empty($row['id']))
		{
			return self::add($row);
		}
		else
		{
			return self::update($row['id'], $row);
		}
	}
	
	public static function update($id, $row)
	{
		$errors = static::validate($row);
		foreach (static::fields() as $key => $field)
		{
			if ($field instanceof relation_Many)
			{
				unset($row[$key]);
			}
			elseif ($field instanceof relation_One && isset($row[$key]))
			{
				$row[$key . '_id'] = (int)$row[$key];
				unset($row[$key]);
			}
		}
		Db::update(self::getTableName(), $id, $row);
	}

	public static function searchIds($where, $bind = array())
	{
		$ids = \Db::fetchCol('SELECT id FROM ' . self::getTableName() . ' WHERE ' . $where, $bind);
		return $ids;
	}
	
	public static function search($where, $bind = array())
	{
		//replacing Model search to cahce results
		$ids = self::searchIds($where, $bind);
		if (empty($ids))
		{
			return $ids;
		}
		
		$rows = \Db::fetchAll('SELECT * FROM ' . self::getTableName() . ' WHERE id IN (' . implode(',', $ids) . ')');
		foreach ($rows as &$row)
		{
			$ids[array_search($row['id'], $ids)] = static::explode($row);
		}
		return $ids;
	}
	
	public static function getById($id)
	{
		$row = \Db::fetchRow('SELECT * FROM ' . self::getTableName() . ' WHERE id=' . $id);
		return static::explode($row);;
	}

}
