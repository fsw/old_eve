<?php

class field_Url extends Field
{
	public function __construct($params = array())
	{
		$this->required = !empty($params['required']);
		$this->minLength = empty($params['minLength']) ? 0 : $params['minLength'];
		$this->maxLength = empty($params['maxLength']) ? 255 : $params['maxLength'];
		$this->placeholder = empty($params['placeholder']) ? '' : $params['placeholder'];
	}

	public function getDbDefinition()
	{
		return 'varchar(' . $this->maxLength . ') ' . ($this->required ? 'NUT NULL' : 'DEFAULT NULL');
	}

	public function validate($data)
	{
		return true;
	}

	public function getJsRegexp()
	{
		return '';
	}
	
	public function getFormInput($key, $value)
	{
		return '<input type="text" name="' . $key . '" value="' . $value . '" placeholder="' . $this->placeholder . '" />';
	}

}
