<?php

class field_Richtext extends field_Text
{
	public function getDbDefinition()
	{
		return 'text';
	}
	
	public function getFormInput($key, $value)
	{
		return '<textarea class="tinymce" name="' . $key . '" placeholder="' . $this->placeholder . '" >' . htmlspecialchars($value) . '</textarea>';
	}
	
	public function fromDb($cell, $key, $code, &$row)
	{
		return Text::deBBCode($cell);
	}
}
