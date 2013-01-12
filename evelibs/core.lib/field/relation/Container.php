<?php

class field_relation_Container extends field_Relation
{
	private $key;
	
	public function __construct($model, $key)
	{
		parent::__construct($model);
		$this->key = $key;
	}
	
	public function getKey()
	{
		return $this->key;	
	}
	
	public function getDbDefinition()
	{
		return null;
	}
	
	public function getFormInput($key, $value)
	{
		$options = $this->getOptions();
		if (empty($value))
		{
			$value = array();
		}
		$ret = '<div>';
		foreach ($options as $k => $v)
		{
			$ret .= '<input type="checkbox" name="' . $key . '[' . $k . ']" value="' . $k . '"' . (in_array($k, $value) ? ' checked="checked"' : '') . '>' . $v . '<br/>';
		}
		$ret .= '</div>';
		return $ret;
	}
	
	public function getLoremIpsum()
	{
		$ammount = rand(1, 3);
		$object = $this->getModel();
		$ret = [];
		for ($i = 0; $i < $ammount; $i++)
		{
			$ret[] = $object->getLoremIpsum();
		}
		return $ret;
	}
}
