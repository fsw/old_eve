<?php

abstract class Field
{
	public function __construct()
	{
	
	}
	
	public function getDbDefinition()
	{
		return 'varchar(255) DEFAULT NULL';
	}
	
	public function getLoremIpsum()
	{
		return 'Lorem Ipsum';
	}
	
	public function validate($data)
	{
		return true;
	}

	public function getFormField($data)
	{
		return '';
	}
	
	public function getHtml($data)
	{
		return $data;
	}

	public function getJsRegexp()
	{
		return '';
	}

	public function serialize($data)
	{
		return $data;
	}

	public function unserialize($data)
	{
		return $data;
	}

}
