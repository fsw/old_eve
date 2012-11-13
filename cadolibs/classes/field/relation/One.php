<?php

class field_relation_One extends field_Relation
{
	public function __construct($model, $field = null)
	{
		$this->model = $model;
		$this->field = $field;
	}
	
	private function printFlat($projects, $value, $depth = 0)
	{
		$ret = '';
		foreach($projects as $project)
		{
			$ret .= '<option value="' . $project['id'] . '"' . ($project['id'] == $value ? ' selected="selected"' : '') . '>' . str_repeat('&nbsp;', $depth) . ($depth ? '-': '') . $project['name'] . '</option>';
			if (!empty($project['children']))
			{
				$ret .= $this->printFlat($project['children'], $value, $depth + 1);
			}
		}
		return $ret;
	}
	
	public function getDbDefinition()
	{
		return 'int(11) NOT NULL';
	}
	
	public static function flatTree($model, $values)
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
	
	public function getFormInput($key, $value)
	{
		$options = array(0 => 'none');
		$object = Site::model($this->model);
		if ($object instanceof model_TreeCollection)
		{ 
			$values = Site::model($this->model)->getTree();
			//var_dump($values);
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
		
		$ret = '<select name="' . $key . '">';
		foreach ($options as $k => $v)
		{
			$ret .= '<option value="' . $k . '"' . ($k == $value ? ' selected="selected"' : '') . '>' . $v . '</option>';
		}
		$ret .= '</select>';
		return $ret;
	}

}
