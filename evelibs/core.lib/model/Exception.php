<?php
/**
 * Exception type that can be thrown by model to indicate user error.
 * 
 * @package Core
 * @author fsw
 */

class model_Exception extends Exception
{
	private $errors = array();
	
	public function __construct($errors)
	{
		$this->errors = is_string($errors) ? [$errors] : $errors;
		parent::__construct('Model exception', 0, null);
	}
	
	public function getErrors()
	{
		return $this->errors;
	}
}
