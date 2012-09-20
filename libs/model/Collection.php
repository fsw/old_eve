<?php

abstract class model_Collection extends Model
{
			
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
		$errors = $this->validate($row);
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
		$this->db->update($this->getTableName(), $id, $row);
	}

	public function searchIds($where, $bind = array())
	{
		$ids = $this->db->fetchCol('SELECT id FROM ' . $this->getTableName() . ' WHERE ' . $where, $bind);
		return $ids;
	}
	
	public function search($where, $bind = array())
	{
		//replacing Model search to cahce results
		$ids = self::searchIds($where, $bind);
		if (empty($ids))
		{
			return $ids;
		}
		
		$rows = $this->db->fetchAll('SELECT * FROM ' . $this->getTableName() . ' WHERE id IN (' . implode(',', $ids) . ')');
		foreach ($rows as &$row)
		{
			$ids[array_search($row['id'], $ids)] = $this->explode($row);
		}
		return $ids;
	}
	
	public function getById($id)
	{
		$row = $this->db->fetchRow('SELECT * FROM ' . $this->getTableName() . ' WHERE id=' . $id);
		return $this->explode($row);;
	}

}
