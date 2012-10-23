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
	
	public function XXXgetFormInput($key, $value)
	{
		$ret = '<select name="' . $key . '">';
		$ret .= '<option value="0">NONE</option>';
		
		//$class = $this->model->getSibling($model);
		$rows = array();
		/*
		if (in_array('TreeCollection', class_parents($class)))
		{
			$rows = $class->getAllAsTree();
		}
		else
		{
			$rows = $class->getAll();
		}
		*/
		$ret .= $this->printFlat($rows, $value);
		$ret .= '</select>';
		return $ret;
	}

}
