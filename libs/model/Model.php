<?php

abstract class Model
{

	protected $db;
	
	public function __construct(Db $db)
	{
		$this->db = $db;
	}
	
	private $fields = null;

	public function fields()
	{
		if ($this->fields === null)
		{
			$this->fields = $this->getFields();
		}
		return $this->fields;
	}
	
	public function getStructure()
	{
		$ret = array();
		$ret[$this->getTableName()] = array();
		foreach ($this->fields() as $key => $field)
		{
			if ($field instanceof Field)
			{
				$ret[$this->getTableName()][$key] = $field->getDbDefinition();
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
	
	protected function getBaseName()
	{
		return strtolower(str_replace('\\', '_', get_called_class()));
	}
	
	protected function getTableName()
	{
		return $this->db->getPrefix() . '_' . self::getBaseName();
	}
	
	protected function validate($row)
	{
		$errors = array();
		foreach ($this->fields() as $key => $field)
		{
			$error = $field->validate(isset($row[$key]) ? $row[$key] : null);
			if ($error !== true)
			{
				$errors[] = $error;
			}
		}
		return $errors;
	}
	
	protected function explode(&$row)
	{
		foreach ($this->fields() as $key => $field)
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
	
	public function search($where, $bind = array())
	{		
		$rows = $this->db->fetchAll('SELECT * FROM ' . $this->getTableName() . ' WHERE ' . $where, $bind);
		//SQL_CALC_FOUND_ROWS
		//SELECT FOUND_ROWS();
		foreach ($rows as &$row)
		{
			$this->explode($row);
		}
		return $rows;
	}

	public function getAll()
	{
		return $this->search('1');
	}
	
	public function searchOne($where, $bind = array())
	{
		$ret = $this->search($where, $bind);
		return reset($ret);
	}
	
	public function add($row)
	{
		$errors = $this->validate($row);
		foreach ($this->fields() as $key => $field)
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
		$this->db->insert(self::getTableName(), $row);
	}
}