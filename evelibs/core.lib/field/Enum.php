<?php

class field_Enum extends Field
{
	public function __construct($values, $multi = false)
	{
		$this->values = $values;
		$this->multi = $multi;
	}
	
	public function fromDb($cell, $key, $code, &$row)
	{
		return $this->multi ? explode(',', $cell) : $cell;
	}
	
	public function toDb($value, $key, $code, &$row)
	{
		return $this->multi ? implode(',', $value) : $value;
	}
	
	public function getLoremIpsum()
	{
		return array_rand($this->values, $this->multi ? rand(1, count($this->values)) : 1);
	}
	
	public function validate($data)
	{
		if ($this->multi)
		{
			return array_diff($data, array_keys($this->values)) ? 'Unknown value' : true;
		}
		else
		{
			return in_array($data, array_keys($this->values)) ? true : 'Unknown value "' . $data . '"';
		}
	}
	
	public function getDbDefinition()
	{
		return ($this->multi ? 'set' : 'enum') . '(\'' . implode('\',\'', array_keys($this->values)) . '\') DEFAULT \'' . current(array_keys($this->values)). '\'';
	}
	
	public function getFormInput($key, $value)
	{
		if ($this->multi)
		{
			if (empty($value))
			{
				$value = array();
			}
			$ret = '<div>';
			foreach ($this->values as $k => $v)
			{
				$ret .= '<input type="checkbox" name="' . $key . '[]" value="' . $k . '"' . (in_array($k, $value) ? ' checked="checked"' : '') . '>' . $v . '<br/>';
			}
			$ret .= '</div>';
		}
		else
		{
			$ret = '<select name="' . $key . '">';
			foreach ($this->values as $k => $v)
			{
				$ret .= '<option value="' . $k . '"' . ($k == $value ? ' selected="selected"' : '') . '>' . $v . '</option>';
			}
			$ret .= '</select>';
		}
		return $ret;
	}
}
