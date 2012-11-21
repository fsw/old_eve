<?php

abstract class field_Relation extends Field
{
	private $model = null;
	
	public function __construct($model)
	{
		$this->model = $model;
	}
	
	private static function flatTree($model, $values)
	{
		$ret = array();
		foreach ($values as $val)
		{
			$ret[$val['id']] = Site::model($model)->getAdminString($val);
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
		$object = Site::model($this->model);
		if ($object instanceof model_TreeCollection)
		{
			$values = Site::model($this->model)->getTree();
			foreach(self::flatTree($this->model, $values) as $id => $name)
			{
				$options[$id] = $name;
			}
		}
		else
		{
			$values = Site::model($this->model)->getAll();
			foreach ($values as $val)
			{
				$options[$val['id']] = Site::model($this->model)->getAdminString($val);
			}
		}
		return $options;
	}
}
