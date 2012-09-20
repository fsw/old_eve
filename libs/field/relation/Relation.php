<?php

abstract class field_Relation extends Field
{
	public function getFormInput($key, $value)
	{
		return '<input type="hidden" name="' . $key . '" value="' . $value . '">';
	}
}
