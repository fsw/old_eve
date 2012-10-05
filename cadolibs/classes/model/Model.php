<?php

abstract class Model
{

	private $privs = array();
	private $errors = array();
	protected $db;
	protected $prefix;
	
	final public function __construct(Db $db, $prefix = 'cado', $privs = null)
	{
		$this->db = $db;
		$this->prefix = $prefix;
		$this->setPrivs($privs);
		$this->init();
	}
	
	final public function setPrivs($privs)
	{
		$this->privs = $privs;
	}
	
	final public function getPrivs()
	{
		return $this->privs;
	}
	
	protected function init()
	{
		//override to add cache
	}
	
	protected function assert($condition, $message)
	{
		if (!$condition)
		{
			throw new model_Exception(array($message));	
		}
	}
	
	protected function assertOne($key, $condition, $message)
	{
		if (!$condition)
		{
			$this->errors[$key] = $message;
		}
	}
	
	protected function checkAsserts()
	{
		if (!empty($this->errors))
		{
			throw new model_Exception($this->errors);
		}
	}
	
	protected function assertPriv($code)
	{
		return $this->assert(in_array($code, $this->privs), 'You dont have privilages to preform this action');
	}

	protected function getBaseName()
	{
		return lcfirst(substr(get_class($this), strlen('model_')));
	}

}
