<?php
/**
 * Database table.
 * 
 * @package Core
 * @author fsw
 * 
 */

abstract class model_Set extends model_Table
{	
	protected $useArrayCache = false;

	protected static function initFields()
	{
		$fields = ['id' => new field_Id()];
		if (!empty(static::$tree))
		{
			$fields['parent'] = new field_relation_One(lcfirst(substr(get_called_class(), strlen('_model'))));
		}
		if (!empty(static::$searchable))
		{
			$fields['solr_status'] = new field_Enum(['to_add' => 'To add', 'to_remove' => 'To remove', 'to_update' => 'To update', 'indexed' => 'Indexed']);
		}
		return $fields;
	}
	
	protected static function initIndexes()
	{
		$indexes = ['primary' => array('id')];
		if (!empty(static::$tree))
		{
			$indexes['parent'] = array(false, 'parent');
		}
		return $indexes;
	}	
	
	public static function canAdmin()
	{
		return in_array('a_' . static::getBaseName(), model_Users::getLoggedInPrivilages());
	}
	
	public static function getAdminString($row)
	{
		return (!empty($row['name']) ? $row['name'] :  
		(!empty($row['title']) ? $row['title'] :
		(!empty($row['code']) ? $row['code'] : $row['id'])));
	}

	public static function getForSelect($field, $key = 'id')
	{
		$all = static::getAll();
		$ret = [];
		foreach($all as $row)
		{
			$ret[$row[$key]] = $row[$field];
		}
		return $ret;
	}
	
	protected static function canAdd($row)
	{
		return static::canAdmin();
	}
	
	protected static function canUpdate($row)
	{
		return static::canAdmin(); 
	}
	
	protected static function canSave($row)
	{
		return empty($row['id']) ? static::canAdd($row) : static::canUpdate($row);
	}
	
	protected static function beforeSave(&$row)
	{
	}
	
	protected static function validate($row)
	{
		$errors = array();
		foreach (static::getFields() as $key => $field)
		{
			if (empty($row['id']) || array_key_exists($key, $row))
			{
				$ret = $field->validate(isset($row[$key]) ? $row[$key] : null);
				static::assertOne($key, $ret === true, $ret);
			}
		}
		static::checkAsserts();
	}

	protected static function explode(&$row)
	{
		foreach (static::getFields() as $key => $field)
		{
			if ($field instanceof field_relation_One)
			{
				$row[$key] = (int)$row[$key];
			}
			elseif ($field instanceof field_relation_Many)
			{
				$row[$key] = static::getDb()->fetchCol(
						'SELECT ' . $key . '_id FROM ' . static::getTableName() . '_xref_' . $key . ' WHERE ' . static::getBaseName() . '_id = ?',
						array($row['id']));
			}
			elseif ($field instanceof field_relation_Container)
			{
				if (!empty($row['id']))
				{
					$className = $field->getModel();
					$row[$key] = $className::search($field->getKey() . ' = ' . $row['id']);
				}
				else
				{
					$row[$key] = array();
				}
			}
			else
			{
				$def = $field->getDbDefinition();
				if (is_array($def))
				{
					$cell = array();
					foreach (array_keys($def) as $subkey)
					{
						$cell[$subkey] = $row[$key . '_' . $subkey];
						unset($row[$key . '_' . $subkey]);
					}
				}
				elseif ($def !== null)
				{
					$cell = $row[$key];
				}
				else
				{
					$cell = null;
				}
				$row[$key] = $field->fromDb($cell, $key, static::getBaseName(), $row);
			}
		}
	}
	
	private static function toDb($row)
	{
		$ret = array();
		foreach (static::getFields() as $key => $field)
		{
			if ($field instanceof field_relation_Many)
			{
				
			}
			elseif ($field instanceof field_relation_One && isset($row[$key]))
			{
				$ret[$key] = (int)$row[$key];
			}
			elseif (array_key_exists($key, $row))
			{
				$cell = $field->toDb($row[$key], $key, static::getBaseName(), $row);
				if (is_array($cell))
				{
					foreach ($cell as $k=>$v)
					{
						$ret[$key . '_' . $k] = $v;
					}
				}
				elseif ($cell !== null)
				{
					$ret[$key] = $cell;
				}
			}
		}
		return $ret;
	}
		
	public static function add(array $row)
	{
		unset($row['id']);
		return static::save($row);
	}

	public static function update($id, array $row)
	{
		$row['id'] = $id;
		return static::save($row);
	}
		
	public static function save(array $row)
	{
		static::beforeSave($row);
		static::validate($row);
		
		$relations = [];
		foreach (static::getFields() as $key => $field)
		{
			if (($field instanceof field_relation_Many) && array_key_exists($key, $row))
			{
				$relations[$key] = $row[$key];
			}
		}
		$row = static::toDb($row);
		if (empty($row['id']))
		{
			static::getDb()->insert(static::getTableName(), $row);
			$row['id'] = static::getDb()->lastInsertId();
		}
		else
		{
			cache_Apc::del(static::getTableName() . '_' . $row['id']);
			//save revision!
			static::getDb()->update(static::getTableName(), $row['id'], $row);
		}
		
		foreach ($relations as $key => $relation)
		{
			static::getDb()->query('DELETE FROM ' . static::getTableName() . '_xref_' . $key . ' WHERE ' .
					static::getBaseName() . '_id = ' . $row['id']);
			foreach ($relation as $r)
			{
				static::getDb()->insert(static::getTableName() . '_xref_' . $key,
						array(
								static::getBaseName() . '_id' => $row['id'],
								$key . '_id' => $r,
						));
			}
		}
		return $row['id'];
	}
	
	public static function searchIds($where = null, $bind = array(), $limit = null, $page = 1, &$foundRows = false)
	{
		$q = 'SELECT ' . ($foundRows === false ? '' : 'SQL_CALC_FOUND_ROWS ') . 'id FROM ' . static::getTableName();
		if ($where !== null)
		{
			$q .= ' WHERE ' . $where;
		}
		if ($limit !== null)
		{
			$q .= ' LIMIT ' . ($limit * ($page - 1)) . ',' . $limit;
		}
		$cacheKey = md5($q . serialize($bind));
		$cached = cache_Apc::get($cacheKey);
		$cached = null;
		if ($cached == null)
		{
			$cached = array();
			$cached['ids'] = static::getDb()->fetchCol($q, $bind);
			$cached['found'] = count($cached['ids']);
			if ($limit !== null)
			{
				if ($cached['found'] == $limit)
				{
					$cached['found'] = static::getDb()->fetchOne('SELECT FOUND_ROWS()');
				}
				else
				{
					$cached['found'] = (($page - 1) * $limit) + $cached['found'];
				}
			}
			cache_Apc::set($cacheKey, $cached, 60);
		}
		if ($foundRows !== false)
		{
			$foundRows = $cached['found'];
		}
		return $cached['ids'];
	}
	
	public static function getAll($limit = null, $page = 1, &$foundRows = false)
	{
		return static::search('1', array(), $limit, $page, $foundRows);
	}
	
	public static function search($where = null, $bind = array(), $limit = null, $page = 1, &$foundRows = false)
	{
		$ids = static::searchIds($where, $bind, $limit, $page, $foundRows);
		return static::getByIds($ids);
	}
	
	public static function searchOne($where = null, $bind = array())
	{
		$rows = static::search($where, $bind);
		if (empty($rows))
		{
			return null;
		}
		return reset($rows);
	}
	
	public static function addLoremIpsum($ammount = 1)
	{
		$ids = [];
		if (static::canAdmin())
		{
			for ($i=0; $i<$ammount; $i++)
			{
				$row = [];
				foreach (static::getFields() as $key => $field)
				{
					$row[$key] = $field->getLoremIpsum();
				}
				$ids[] = static::add($row); 	
			}
		}
		else
		{
			throw new model_Exception('Permission denied');
		}
		return $ids;
	}
	
	public static function getById($id)
	{
		$ret = static::getByIds([$id]);
		return empty($ret[0]) ? null : $ret[0];
	}
	
	public static function getByIds($ids)
	{
		if (!empty($ids))
		{
			$keyBase = static::getTableName() . '_';
			$cacheKeys = array();
			foreach ($ids as $id)
			{
				$cacheKeys[] = $keyBase . $id;
			}
			$cache = cache_Apc::get($cacheKeys);
			$dbIds = array();
			foreach($ids as $key => $id)
			{
				$found = false;
				if (!empty($cache))
				{
					foreach($cache as $row)
					{
						if ($row['id'] === $id)
						{
							$ids[$key] = $row;
							$found = true;
							break;
						}
					}
				}
				if (!$found)
				{
					$dbIds[] = $id;
				}
			}
			//die();
			if (!empty($dbIds))
			{
				$rows = static::getDb()->fetchAll('SELECT * FROM ' . static::getTableName() . ' WHERE id IN (' . implode(',', $dbIds) . ')');
				foreach ($ids as &$id)
				{
					if (is_numeric($id))
					{
						$found = false;
						foreach ($rows as $row)
						{
							if ($row['id'] == $id)
							{
								$found = true;
								static::explode($row);
								cache_Apc::set($keyBase . $id, $row, 60);
								$id = $row;
								break;
							}
						}
						if (!$found)
						{
							$id = false;
						}
					}
				}
			}
		}
		return $ids;
	}
	
	public static function getByField($key, $value)
	{
		return static::searchOne('`' . $key . '` = ?', array($value));
	}
	
	public static function deleteById($id)
	{
		static::getDb()->query('DELETE FROM ' . static::getTableName() . ' WHERE id=' . $id);
		return true;
	}
	
}
