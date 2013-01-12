<?php
/**
 * Database table.
 * 
 * @package Core
 * @author fsw
 * 
 */

trait model_set_Tree
{
	protected static $tree = true;
	

	
	private static function saveFlat(&$list, $tree, $key = 'id', $field = 'name', $depth = 0)
	{
		foreach ($tree as $elem)
		{
			$list[$elem[$key]] = str_repeat('- ', $depth) . $elem[$field];
			if (!empty($elem['children']))
			{
				static::saveFlat($list, $elem['children'], $key, $field, $depth + 1);
			}
		} 
	}
	
	public static function getForSelect($field, $key = 'id')
	{
		$all = static::getTree();
		$list = array();
		static::saveFlat($list, $all, $key, $field);
		return $list;
	}
	

	public static function getChildren($parentId = 0, $where = '1', $limit = null, $page = 1, &$foundRows = false)
	{
		return static::search('`parent` = ? AND ' . $where, array($parentId), $limit, $page, $foundRows);
	}
	
	public static function getPath($id = 0)
	{
		$ret = array();
		while ($id != 0)
		{
			 $c = static::getById($id);
			 $ret[] = $c;
			 $id = $c['parent'];
		}
		return array_reverse($ret);
	}
	
	public static function getTree($parentId = 0, $where = '1')
	{
		$rows = static::getChildren($parentId, $where);
		foreach($rows as &$row)
		{
			if(!empty($row['id']))
			{
				$row['children'] = static::getTree($row['id'], $where);
			}
		}
		return $rows;
		/*
		$all = static::getAll(100);
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
