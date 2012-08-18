<?php

class field_Email extends field_Text
{
	public function validate($data)
	{
		if (!filter_var($data, FILTER_VALIDATE_EMAIL))
		{
			return new FieldError('Email "' . $data . '" not valid');
		}
		return true;
	}
}
