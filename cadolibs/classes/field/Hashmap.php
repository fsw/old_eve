<?php

class field_Hashmap extends Field
{
	public function __construct()
	{
		
	}
	
	public function getDbDefinition()
	{
		return 'int(11) NOT NULL, varchar(32) NOT NULL';
	}
}
