<?php

class field_Float extends Field
{
	public function __construct()
	{
		
	}
	
	public function getDbDefinition()
	{
		return 'int(11) NOT NULL';
	}
}
