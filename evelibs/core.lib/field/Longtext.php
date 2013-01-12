<?php

class field_Longtext extends field_Text
{
	public function getDbDefinition()
	{
		return 'text NOT NULL';
	}
	
	public function getFormInput($key, $value)
	{
		return '<textarea name="' . $key . '">' . htmlspecialchars($value) . '</textarea>';
	}
}
