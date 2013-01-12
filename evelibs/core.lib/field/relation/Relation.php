<?php

abstract class field_Relation extends Field
{
	private $model = null;
	
	public function __construct($model)
	{
		$this->model = $model;
	}
	
	public function getModel()
	{
		return 'model_' . ucfirst($this->model);
	}
	
	private static function flatTree($model, $values)
	{
		$ret = array();
		foreach ($values as $val)
		{
			$ret[$val['id']] = $model::getAdminString($val);
			foreach(self::flatTree($model, $val['children']) as $id => $name)
			{
				$ret[$id] = ' &raquo; ' . $name;
			}
		}
		return $ret;
	}
	
	protected function getOptions()
	{
		$options = array(0 => 'none');
		$object = $this->getModel();
		if ($object instanceof model_set_Tree)
		{
			$values = $object::getTree();
			//var_dump($values);
			foreach(self::flatTree($object, $values) as $id => $name)
			{
				$options[$id] = $name;
			}
		}
		else
		{
			$values = $object::getAll();
			foreach ($values as $val)
			{
				$options[$val['id']] = $object::getAdminString($val);
			}
		}
		return $options;
	}
}
