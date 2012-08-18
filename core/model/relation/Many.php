<?php

class relation_Many extends Relation
{
	public function __construct()
	{

	}
	
	public function getFormInput($key, $value)
	{
		return '<input type="text" name="' . $key . '" value="">';
	}

}
