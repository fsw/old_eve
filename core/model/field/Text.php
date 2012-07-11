<?php

class field_Text extends Field
{
	public function __construct()
	{
		//$this->minLength = $minLength;
		//$this->maxLength = $maxLength;
	}

	public function getDbDefinition()
	{
		return 'varchar(255) DEFAULT NULL';
	}

	public function validate($data)
	{
		return true;
	}

	public function getJsRegexp()
	{
		return '';
	}

}
