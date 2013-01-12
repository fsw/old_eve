<?php

class field_relation_Many extends field_Relation
{
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
		$options = $this->getOptions();
		return array_rand($options, rand(1, count($options)));
	}
}
