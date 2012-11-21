<?php
/**
 * Database table.
 * 
 * @package CadoLibs
 * @author fsw
 */

abstract class model_Collection extends Model
{
	private $fields = null;
	private $indexes = null;
	
	protected $useArrayCache = false;

	protected function initFields()
	{
		return array(
				'id' => new field_Id()
		);
	}
	
	protected function initIndexes()
	{
		return array(
				'primary' => array('id')
		);
	}
	
	public function getFields()
	{
		if ($this->fields === null)
		{
			$this->fields = $this->initFields();
		}
		return $this->fields;
	}
	
	public function getField($key)
	{
		if ($this->fields === null)
		{
			$this->fields = $this->initFields();
		}
		return empty($this->fields[$key]) ? null : $this->fields[$key];
	}
	
	public function getIndexes()
	{
		if ($this->indexes === null)
		{
			$this->indexes = $this->initIndexes();
		}
		return $this->indexes;
	}
	
	public function getStructure()
	{
		$ret = array();
		$ret[$this->getTableName()] = array();
		foreach ($this->getFields() as $key => $field)
		{
			
			if ($field instanceof field_relation_One)
			{
				$ret[$this->getTableName()][$key] = 'int(11) DEFAULT NULL';
			}
			elseif ($field instanceof field_relation_Many)
			{
				$f = new field_Int();
				$ret[$this->getTableName() . '_xref_' . $key] = array(
						$this->getBaseName() . '_id' => $f->getDbDefinition(),
						$key . '_id' => $f->getDbDefinition()
				);
			}
			else
			{
				$definition = $field->getDbDefinition();
				if (is_array($definition))
				{
					foreach ($definition as $subkey => $def)
					{
						$ret[$this->getTableName()][$key . '_' . $subkey] = $def;
					}
				}
				else
				{
					$ret[$this->getTableName()][$key] = $definition;
				}
			}
		}
		foreach ($this->getIndexes() as $key => $field)
		{
			$unique = ($key == 'primary') || array_shift($field);
			$ret[$this->getTableName()]['index_' . $key] =
			($key == 'primary' ? 'PRIMARY KEY' : (($unique ? 'UNIQUE ' : '') . 'KEY ' . '`index_' . $key . '`'))
			. ' (`' . implode($field , '`,`') . '`)';
		}
		return $ret;
	}
	
	public function getAdminString($row)
	{
		return (!empty($row['name']) ? $row['name'] :  
		(!empty($row['title']) ? $row['title'] :
		(!empty($row['code']) ? $row['code'] : $row['id'])));
	}
	
	public function getTableName()
	{
		return $this->prefix . '_' . $this->getBaseName();
	}
	
	protected function canSave(&$row)
	{
	}
	
	protected function beforeSave(&$row)
	{
	}
	
	protected function validate($row)
	{
		$errors = array();
		foreach ($this->getFields() as $key => $field)
		{
			$ret = $field->validate(isset($row[$key]) ? $row[$key] : null);
			$this->assertOne($key, $ret === true, $ret);
		}
		$this->checkAsserts();
	}

	protected function explode(&$row)
	{
		foreach ($this->getFields() as $key => $field)
		{
			if ($field instanceof field_relation_One)
			{
				$row[$key] = (int)$row[$key];
			}
			elseif ($field instanceof field_relation_Many)
			{
				$row[$key] = $this->db->fetchCol(
						'SELECT ' . $key . '_id FROM ' . $this->getTableName() . '_xref_' . $key . ' WHERE ' . $this->getBaseName() . '_id = ?',
						array($row['id']));
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
				else
				{
					$cell = $row[$key];
				}
				$row[$key] = $field->fromDb($cell);
			}
		}
	}
	
	/*
	protected function implode(&$row)
	{
		var_dump("IMPLODING", $row);
		die();
		foreach ($this->getFields() as $key => $field)
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
		foreach ($row as $key=>$value)
		{
			$cell = $this->getFields($key)->toDb($value);
			if (is_array($cell))
			{
				foreach ($cell as $k=>$v)
				{
					$row[$key . '_' . $k] = $v;
				}
			}
			else
			{
				$row[$key] = $cell;
			}
		}
	}*/
	
	private function toDb($row)
	{
		$ret = array();
		foreach ($this->getFields() as $key => $field)
		{
			if ($field instanceof field_relation_Many)
			{
				if (!empty($row['id']) && isset($row[$key]) && is_array($row[$key]))
				{
					$this->db->query('DELETE FROM ' . $this->getTableName() . '_xref_' . $key . ' WHERE ' .
							$this->getBaseName() . '_id = ' . $row['id']);
					foreach ($row[$key] as $r)
					{
						$this->db->insert($this->getTableName() . '_xref_' . $key,
								array(
									$this->getBaseName() . '_id' => $row['id'],
									$key . '_id' => $r,
						));
					}
					//var_dump($row[$key]);
					//die();
				}
			}
			elseif ($field instanceof field_relation_One && isset($row[$key]))
			{
				$ret[$key] = (int)$row[$key];
			}
			elseif (array_key_exists($key, $row))
			{
				$cell = $field->toDb($row[$key]);
				if (is_array($cell))
				{
					foreach ($cell as $k=>$v)
					{
						$ret[$key . '_' . $k] = $v;
					}
				}
				else
				{
					$ret[$key] = $cell;
				}
			}
		}
		return $ret;
	}
		
	public function add(array $row)
	{
		$this->beforeSave($row);
		$errors = $this->validate($row);
		if (empty($errors))
		{
			$row = $this->toDb($row);
			$this->db->insert(self::getTableName(), $row);
			$ret = $this->db->lastInsertId();
			$row['id'] = $ret;
			$this->toDb($row);
			return $ret;
		}
		else
		{
			return $errors;
		}
	}

	public function save(array $row)
	{
		if (empty($row['id']))
		{
			return $this->add($row);
		}
		else
		{
			return $this->update($row['id'], $row);
		}
	}

	public function update($id, array $row)
	{
		cache_Apc::del($this->getTableName() . '_' . $id);
		//$errors = $this->validate($row);
		$this->beforeSave($row);
		$row = $this->toDb($row);
		$this->db->update($this->getTableName(), $id, $row);
		return true;
	}

	public function searchIds($where = null, $bind = array(), $limit = null, $page = 1, &$foundRows = false)
	{
		$q = 'SELECT ' . ($foundRows === false ? '' : 'SQL_CALC_FOUND_ROWS ') . 'id FROM ' . $this->getTableName();
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
			$cached['ids'] = $this->db->fetchCol($q, $bind);
			$cached['found'] = count($cached['ids']);
			if ($limit !== null)
			{
				if ($cached['found'] == $limit)
				{
					$cached['found'] = $this->db->fetchOne('SELECT FOUND_ROWS()');
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
	
	public function getAll($limit = null, $page = 1, &$foundRows = false)
	{
		return $this->search('1', array(), $limit, $page, $foundRows);
	}
	
	public function search($where = null, $bind = array(), $limit = null, $page = 1, &$foundRows = false)
	{
		$ids = $this->searchIds($where, $bind, $limit, $page, $foundRows);
		return $this->getByIds($ids);
	}
	
	public function searchOne($where = null, $bind = array())
	{
		$rows = $this->search($where, $bind);
		if (empty($rows))
		{
			return null;
		}
		return reset($rows);
	}
	
	public function getDefaults()
	{
		//lorem ipsums
		return array();
	}
	
	public function getById($id)
	{
		return $this->searchOne('id = ?', array($id));
	}
	
	public function getColByIds($col, $ids)
	{
		$rows = $this->getByIds($ids);
		foreach ($rows as &$row)
		{
			$row = $row[$col];
		}
		return $rows;
	}
	
	public function getByIds($ids)
	{
		if (!empty($ids))
		{
			$keyBase = $this->getTableName() . '_';
			$cacheKeys = array();
			foreach ($ids as $id)
			{
				$cacheKeys[] = $keyBase . $id;
			}
			//var_dump($cacheKeys);
			$cache = cache_Apc::get($cacheKeys);
			//var_dump($cache);
			$dbIds = array();
			foreach($ids as &$id)
			{
				$found = false;
				if (!empty($cache))
				{
					foreach($cache as $row)
					{
						if ($row['id'] === $id)
						{
							$id = $row;
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
			//var_dump($ids);
			//var_dump($dbIds);
			//die();
			if (!empty($dbIds))
			{
				$rows = $this->db->fetchAll('SELECT * FROM ' . $this->getTableName() . ' WHERE id IN (' . implode(',', $dbIds) . ')');
				foreach($rows as $row)
				{
					foreach($ids as &$id)
					{
						if (is_numeric($id) && $row['id'] === $id)
						{
							$this->explode($row);
							cache_Apc::set($keyBase . $id, $row, 60);
							$id = $row;
							break;
						}
					}
				}
			}
			//var_dump($ids);
		}
		return $ids;
	}
	
	public function getByField($key, $value)
	{
		return $this->searchOne('`' . $key . '` = ?', array($value));
	}
	
	public function deleteById($id)
	{
		$this->db->query('DELETE FROM ' . $this->getTableName() . ' WHERE id=' . $id);
		return true;
	}
	
	public function deleteByField($key, $value)
	{
		$this->db->query('DELETE FROM ' . $this->getTableName() . ' WHERE `' . $key . '`= ?', array($value));
		return true;
	}
	

}
