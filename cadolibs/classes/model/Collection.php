<?php

abstract class model_Collection extends Model
{
	private $fields = null;
	
	public function fields()
	{
		if ($this->fields === null)
		{
			$this->fields = $this->getFields();
		}
		return $this->fields;
	}
	
	public function field($key)
	{
		if ($this->fields === null)
		{
			$this->fields = $this->getFields();
		}
		return $this->fields[$key];
	}
	
	public function getStructure()
	{
		$ret = array();
		$ret[$this->getTableName()] = array();
		foreach ($this->fields() as $key => $field)
		{
			if ($field instanceof Field)
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
			elseif ($field instanceof relation_One)
			{
				$ret[$this->getTableName()][$key . '_id'] = 'int(11) DEFAULT NULL';
			}
			elseif ($field instanceof relation_Many)
			{
				$f = new field_Int();
				$ret[$this->getTableName() . '_xref_' . $key] = array(
						$this->getBaseName() . '_id' => $f->getDbDefinition(),
						$key . '_id' => $f->getDbDefinition()
				);
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

	public function getTableName()
	{
		return $this->prefix . '_' . $this->getBaseName();
	}

	protected function validate($row)
	{
		$errors = array();
		foreach ($this->fields() as $key => $field)
		{
			$ret = $field->validate(isset($row[$key]) ? $row[$key] : null);
			$this->assertOne($key, $ret === true, $ret);
		}
		$this->checkAsserts();
	}

	protected function explode(&$row)
	{
		foreach ($this->fields() as $key => $field)
		{
			if ($field instanceof Field)
			{
				$def = $field->getDbDefinition();
				if (is_array($def))
				{
					$cell = array();
					foreach (array_keys($def) as $subkey)
					{
						$cell[$subkey] = $row[$key . '_' . $subkey];
					}
				}
				else
				{
					$cell = $row[$key];
				}
				$row[$key] = $field->fromDb($cell);
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
	}
	
	/*
	protected function implode(&$row)
	{
		var_dump("IMPLODING", $row);
		die();
		foreach ($this->fields() as $key => $field)
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
			$cell = $this->field($key)->toDb($value);
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
		foreach ($this->fields() as $key => $field)
		{
			if ($field instanceof relation_Many)
			{
				
			}
			elseif ($field instanceof relation_One && isset($row[$key]))
			{
				$ret[$key . '_id'] = (int)$row[$key];
			}
			elseif (isset($row[$key]))
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
		
	public function add($row)
	{
		$errors = $this->validate($row);
		if (empty($errors))
		{
			$row = $this->toDb($row);
			$this->db->insert(self::getTableName(), $row);
			return true;
		}
		else
		{
			return $errors;
		}
	}
	
	protected function getFields()
	{
		return array(
				'id' => new field_Id()
		);
	}

	protected function getIndexes()
	{
		return array(
				'primary' => array('id')
		);
	}

	public function save($row)
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

	public function update($id, $row)
	{
		//$errors = $this->validate($row);
		$row = $this->toDb($row);
		$this->db->update($this->getTableName(), $id, $row);
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
		$ids = $this->db->fetchCol($q, $bind);
		if ($foundRows !== false)
		{
			$foundRows = $this->db->fetchOne('SELECT FOUND_ROWS()');
		}
		return $ids;
	}
	
	public function getAll($limit = null, $page = 1, &$foundRows = false)
	{
		return $this->search('1', array(), $limit, $page, $foundRows);
	}
	
	public function search($where = null, $bind = array(), $limit = null, $page = 1, &$foundRows = false)
	{
		$ids = $this->searchIds($where, $bind, $limit, $page, $foundRows);
		if (empty($ids))
		{
			$rows = array();
		}
		else
		{
			$rows = $this->db->fetchAll('SELECT * FROM ' . $this->getTableName() . ' WHERE id IN (' . implode(',', $ids) . ')');
			foreach($ids as &$id)
			{
				foreach($rows as $row)
				{
					if ($row['id'] === $id)
					{
						$id = $row;
						break;
					}
				}
			}
		}
		foreach ($ids as &$row)
		{
			$this->explode($row);
		}
		return $ids;
	}
	
	public function searchOne($where = null, $bind = array())
	{
		$rows = $this->search($where, $bind);
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
