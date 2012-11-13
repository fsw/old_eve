<?php
/**
 * @package CadoLibs
 * @author fsw
 */

class Form extends Widget
{
	protected $elements = array();
	protected $errors = array();
	
	static $counter;
	
	public function __construct($name = 'form', $template = 'form')
	{
		parent::__construct($template);
		$this->name = $name;
		
		$this->elements = array();
		$this->errors = array();
		
		$this->submitText = 'Submit';
	}
	
	public function addErrors($array)
	{
		//TODO
		$this->errors = array_merge($this->errors, $array);
	}
	
	public function addError($message)
	{
		$this->addErrors(array($message));
	}
	
	public function addElements($elements)
	{
		foreach ($elements as $key => $element)
		{
			if ($element instanceof Field)
			{
				$element = array('field' => $element);
			}
			if (empty($element['title']))
			{
				$element['title'] = $key;
			}
			if (empty($element['desc']))
			{
				$element['desc'] = '';
			}
			$element['value'] = null;
			$this->elements[$key] = $element;
		}		
	}
	
	public function addElement($key, $element)
	{
		$this->addElements(array($key => $element));
	}
	
	public function setValues($values)
	{
		foreach ($values as $key=>$value)
		{
			if (!empty($this->elements[$key]))
			{
				$this->elements[$key]['value'] = $value;			
			}
			else
			{
				//TODO after fixing expand/collapse
				//throw new Exception('unknown element "' . $key . '"');
			}
		}
	}
	
	public function getValues()
	{
		$ret = array();
		foreach ($this->elements as $key=>$value)
		{
			$ret[$key] = $this->elements[$key]['value'];
		}
		return $ret;
	}
	
	public function getValue($key)
	{
		return $this->elements[$key]['value'];
	}
	
	public function isSubmitted()
	{
		return !empty($_POST[$this->name]);
	}
	
	public function validate()
	{
		if (!empty($_POST[$this->name]))
		{
			$valid = true;
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
			
			foreach ($this->elements as $key => &$element)
			{
				$val = array_key_exists($key, $_POST[$this->name]) ? $_POST[$this->name][$key] : null;
				$element['value'] = $element['field']->fromPost($val);
				$error = $element['field']->validate($element['value']);
				if ($error !== true)
				{
					$element['error'] = $error;
					$valid = false;
				}
			}
			
			return $valid;
		}
		return false;
	}
		
}