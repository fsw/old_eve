<?php
/**
 * @package Core
 * @author fsw
 */

class Form extends Template
{
	protected $elements = array();
	protected $errors = array();
	protected $valid = true;
	
	static $counter;
	
	public function __construct($name = 'form', $template = 'form/form.html')
	{
		parent::__construct($template);
		$this->name = $name;
		
		$this->elements = array();
		$this->errors = array();
		$this->id = '';
		
		$this->submitText = 'Submit';
	}
	
	public function addErrors($array)
	{
		//TODO
		$this->valid = false;
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
	
	private function filesData(&$name, &$type, &$tmp_name, &$error, &$size)
	{
		if (is_scalar($name) && ($error == UPLOAD_ERR_NO_FILE))
		{
			return null;
		}
		elseif (is_scalar($name))
		{
			return ['name' => $name, 'type' => $type, 'tmp_name' => $tmp_name, 'error' => $error, 'size' => $size];
		}
		else
		{
			$ret = [];
			foreach ($name as $key => $dummy)
			{
				$elem = $this->filesData($name[$key], $type[$key], $tmp_name[$key], $error[$key], $size[$key]);
				if ($elem !== null)
				{
					$ret[$key] = $elem;
				}
			}
			return $ret;
		}
	}
	
	public function isValid()
	{
		return $this->valid;
	}
	
	public function validate()
	{
		if (!empty($_POST[$this->name]))
		{
			if (!empty($_FILES[$this->name]))
			{
				$_POST[$this->name] = array_merge_recursive(
						$_POST[$this->name], 
						$this->filesData(
								$_FILES[$this->name]['name'],
								$_FILES[$this->name]['type'],
								$_FILES[$this->name]['tmp_name'],
								$_FILES[$this->name]['error'],
								$_FILES[$this->name]['size']
						)
				);
				/*foreach (array('name', 'type', 'size', 'tmp_name', 'error') as $field)
				{
					foreach ($_FILES[$this->name][$field] as $key=>$value)
					{
						$_POST[$this->name][$key][$field] = $value;
					}
				}*/
			}
			
			foreach ($this->elements as $key => &$element)
			{
				$val = array_key_exists($key, $_POST[$this->name]) ? $_POST[$this->name][$key] : null;
				$element['value'] = $element['field']->fromPost($val);
				$error = $element['field']->validate($element['value']);
				if ($error !== true)
				{
					$element['error'] = $error;
					$this->valid = false;
				}
			}
			
			return $this->valid;
		}
		return false;
	}
		
}