<?php
/**
 * 
 * @author fsw
 *
 */
class Form extends Widget
{
	//protected $fields = array();
	protected $data = array();
	//protected $errors = array();
	
	static $counter;
	
	public function __construct($path = 'form')
	{
		parent::__construct($path);
		$this->name = 'form' . (++static::$counter);
		$this->errors = array();
		$this->submitText = 'Submit';	
		$this->fields = array();
		$this->data = array();
	}
	
	public function addErrors($array)
	{
		$this->errors = array_merge($this->errors, $array);
	}
	
	public function addError($msg)
	{
		$this->addErrors(array($msg));
	}
	
	public function setErrors($array)
	{
		$this->errors = $array;
	}
	
	public function setFields($fields)
	{
		$this->fields = $fields;
	}
	
	public function setData($data)
	{
		$this->data = $data;
	}
	
	public function getData()
	{
		return $this->data;
	}
	
	public function getField($field)
	{
		return $this->data[$field];
	}
	
	public function submitted()
	{
		return !empty($_POST[$this->name]);
	}
	
	public function validate()
	{		
		if (!empty($_POST[$this->name]))
		{
			if (!empty($_FILES[$this->name]))
			{
				foreach (array('name', 'type', 'size', 'tmp_name', 'error') as $field)
				{
					foreach ($_FILES[$this->name][$field] as $key=>$value)
					{
						$_POST[$this->name][$key][$field] = $value;
					}
				}
			}
			//var_dump($_POST);
			//die();
			$data = $this->data;
			foreach ($this->fields as $key => $field)
			{
				$data[$key] = $field->fromPost(empty($_POST[$this->name][$key]) ? null : $_POST[$this->name][$key]);
				$error = $field->validate($data[$key]);
				if ($error !== true)
				{
					//TODO
					$tmp = $this->errors;
					$tmp[$key] = $error;
					$this->errors = $tmp;
				}
			}
			$this->data = $data;
			//unset($data['token']);
			if (empty($this->errors))
			{
				return true;
			}
		}
		return false;
	}
		
}