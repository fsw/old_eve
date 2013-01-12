<?php

class field_Bool extends Field
{	
	private $default;
	
	public function __construct($default = false)
	{
		$this->default = $default;
	}
	
	public function getDbDefinition()
	{
		return 'tinyint(1) NOT NULL' . ($this->default ? ' DEFAULT 1' : '');
	}
	
	public function getFormInput($key, $value)
	{
		if ($value===null && $this->default)
		{
			$value = 1;
		}
		return '<input type="hidden" name="' . $key . '" value="0"><input type="checkbox" name="' . $key . '" value="1"' . (!empty($value) ? ' checked="checked"' : '') . '>';
	}
	
	public function toDb($value, $key, $code, &$row)
	{
		return empty($value) ? 0 : 1;
	}
	
	public function getLoremIpsum()
	{
		return rand(0, 1);
	}
}
