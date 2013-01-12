<?php

date_default_timezone_set('UTC');

class field_Timestamp extends Field
{
	public function __construct($flags = 0)
	{
		//$this->required = !($flags & Field::OPTIONAL);
	}
	
	public function getDbDefinition()
	{
		return 'datetime NOT NULL';
	}
	
	public function getFormInput($key, $value)
	{
		return '<input class="datepicker" type="text" name="' . $key . '" value="' . $value . '">';
	}
	
	public function fromDb($cell, $key, $code, &$row)
	{
		return strtotime($cell) ?: 0;
	}
}
