<?php

class field_Password extends field_Text
{
	public function getFormInput($key, $value)
	{
		return '<input type="password" name="' . $key . '">';
	}
	
	public function validate($data)
	{
		if (strlen($data) < 8)
		{
			return new FieldError('Password needs to be at least 8 chars long');
		}
		return true;
	}
	
}
