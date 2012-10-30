<?php

class field_Bool extends Field
{	
	public function getDbDefinition()
	{
		return 'tinyint(1) NOT NULL';
	}
	
	public function getFormInput($key, $value)
	{
		return '<input type="hidden" name="' . $key . '" value="0"><input type="checkbox" name="' . $key . '" value="1"' . (!empty($value) ? ' checked="checked"' : '') . '>';
	}
	
	public function toDb($value)
	{
		return empty($value) ? 0 : 1;
	}
}
