<?php

abstract class Cell
{
	public function __construct()
	{
	
	}
	
	public function getFormInput($key, $value)
	{
		return '<input type="hidden" name="' . $key . '" value="' . $value . '">';
	}
	
	public function validate($data)
	{
		return true;
	}
	
}
