<?php
/**
 * Database table.
 * 
 * @package CadoLibs
 * @author fsw
 */

abstract class model_TreeCollection extends model_Collection
{
	protected function getFields()
	{
		return array_merge(
			parent::getFields(),
			array(
				'parent' => new field_relation_One(lcfirst(substr(get_called_class(), strlen('_model')))),
			)
		);
	}
	
	protected function getIndexes()
	{
		return array_merge(
				parent::getIndexes(),
				array(
					'parent' => array(false, 'parent')
				)
		);
	}
	public function getChildren($parentId = 0, $where = '1', $limit = null, $page = 1, &$foundRows = false)
	{
		return $this->search('`parent` = ? AND ' . $where, array($parentId), $limit, $page, $foundRows);
	}
	
	public function getPath($id = 0)
	{
		$ret = array();
		while ($id != 0)
		{
			 $c = $this->getById($id);
			 $ret[] = $c;
			 $id = $c['parent'];
		}
		return array_reverse($ret);
	}
	
	public function getTree($parentId = 0, $where = '1')
	{
		$rows = $this->getChildren($parentId, $where);
		foreach($rows as &$row)
		{
			if(!empty($row['id']))
			{
				$row['children'] = $this->getTree($row['id'], $where);
			}
		}
		return $rows;
		/*
		$all = $this->getAll(100);
		var_dump($all);
		$ret = array();
		$paths = array();
		$found = true;
		while (count($all) && $found)
		{
			$found = false;
			foreach($all as $key=>$row)
			{
				if (empty($row['parent']))
				{
					$row['children'] = array();
					$ret[$row['id']] = $row;
					$paths[$row['id']] = array();
					unset($all[$key]);
					$found = true;
				}
				elseif (isset($paths[$row['parent']]))
				{
					$paths[$row['id']] = array_merge($paths[$row['parent']], array($row['parent']));
					$arr = &$ret;
					foreach($paths[$row['id']] as $id)
					{
						$arr = &$arr[$id]['children'];
					}
					$row['children'] = array();
					$arr[$row['id']] = $row;
					unset($all[$key]);
					$found = true;
				}
			}
		}
		return $ret;
		*/
	}
}
