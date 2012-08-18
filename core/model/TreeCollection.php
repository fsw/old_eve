<?php

abstract class TreeCollection extends Collection
{
	protected static function getFields()
	{
		return array_merge(
			parent::getFields(),
			array(
				'parent' => new relation_One(get_called_class()),
			)
		);
	}
	
	public static function getAllAsTree()
	{
		$all = static::getAll();
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
	}
}
