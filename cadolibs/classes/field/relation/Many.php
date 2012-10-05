<?php

class field_relation_Many extends field_Relation
{
	public function __construct()
	{

	}
	
	public function getFormInput($key, $value)
	{
		return '<input type="text" name="' . $key . '" value="">';
	}

}
