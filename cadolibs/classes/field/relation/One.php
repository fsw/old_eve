<?php

class field_relation_One extends field_Relation
{
	private $optional;
	
	public function __construct($model, $optional = false)
	{
		parent::__construct($model);
		$this->optional = $optional;
	}
	
	public function getDbDefinition()
	{
		return 'int(11) NOT NULL';
	}
	
	public function getFormInput($key, $value)
	{
		$options = $this->getOptions();
		$ret = '<select name="' . $key . '">';
		foreach ($options as $k => $v)
		{
			$ret .= '<option value="' . $k . '"' . ($k == $value ? ' selected="selected"' : '') . '>' . $v . '</option>';
		}
		$ret .= '</select>';
		return $ret;
	}

}
