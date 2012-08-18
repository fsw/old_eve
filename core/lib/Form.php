<?php
/**
 * 
 * @author fsw
 *
 */
class Form extends Widget
{
	public $fields = array();
	
	public function handlePost($data)
	{
		$errors = array();
		foreach ($this->fields() as $key => $field)
		{
			$error = $field->validate(isset($data[$key]) ? $data[$key] : null);
			if ($error !== true)
			{
				$errors[$key] = $error;
			}
		}
		return $errors;
	}
	
	public function fields()
	{
		//TODO ???
		if (empty($this->fields))
		{
			$this->fields = $this->getFields();
		}
		return $this->fields; 
	}
	
	protected function getFields()
	{
		return $this->fields;
	}
	
	public function setFields($fields)
	{
		$this->fields = $fields;
	}
	
	public function __construct($data = array())
	{
		parent::__construct('form');
		$this->fields = $this->fields();
		$this->valid = false;
		$this->name = 'todo';
		$this->data = $data;
		if (!empty($_POST[$this->name]))
		{
			$this->errors = $this->handlePost($_POST[$this->name]);
			$this->data = array_merge($this->data, $_POST[$this->name]);
			if (empty($this->errors))
			{
				$this->valid = true;
			}
		}
		else
		{
			$this->errors = array();
		}
	}
	
}