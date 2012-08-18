<?php

class field_Longtext extends field_Text
{
	public function getDbDefinition()
	{
		return 'text';
	}
	
	public function getFormInput($key, $value)
	{
		return '<textarea name="' . $key . '">' . $value . '</textarea>';
	}
}
