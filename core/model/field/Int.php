<?php

class field_Int extends Field
{
	public function __construct()
	{
		
	}
	
	public function getDbDefinition()
	{
		return 'int(11) NOT NULL';
	}

}
