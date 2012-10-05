<?php

class field_Enum extends Field
{
	public function __construct($values)
	{
		$this->values = $values;
	}
	
	public function validate($data)
	{
		if (!in_array($data, array_keys($this->values)))
		{
			return 'Unknown value "' . $data . '"';
		}
		return true;
	}
	
	public function getDbDefinition()
	{
		return 'enum(\'' . implode('\',\'', array_keys($this->values)) . '\') DEFAULT \'' . current(array_keys($this->values)). '\'';
	}
	
	public function getFormInput($key, $value)
	{
		$ret = '<select name="' . $key . '">';
		foreach ($this->values as $k => $v)
		{
			$ret .= '<option value="' . $k . '"' . ($k == $value ? ' selected="selected"' : '') . '>' . $v . '</option>';
		}
		$ret .= '</select>';
		return $ret;
	}
}
