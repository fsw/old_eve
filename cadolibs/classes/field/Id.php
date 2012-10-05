<?php

class field_Id extends field_Int
{
	public function getDbDefinition()
	{
		return 'int(11) NOT NULL AUTO_INCREMENT';
	}

	public function getFormInput($key, $value)
	{
		return '<input type="hidden" name="' . $key . '" value="' . $value . '">#' . $value;
	}
}
