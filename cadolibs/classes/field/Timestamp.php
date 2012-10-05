<?php

class field_Timestamp extends Field
{
	public function __construct($flags = 0)
	{
		$this->required = !($flags & Field::OPTIONAL);
	}
	
	public function getDbDefinition()
	{
		return 'timestamp ' . ($this->required ? 'NOT NULL DEFAULT CURRENT_TIMESTAMP' : 'NULL DEFAULT NULL');
	}
	
	public function getFormInput($key, $value)
	{
		return '<input class="datepicker" type="text" name="' . $key . '" value="' . $value . '">';
	}
	
	public function unserialize($data)
	{
		return strtotime($data);
	}
}
