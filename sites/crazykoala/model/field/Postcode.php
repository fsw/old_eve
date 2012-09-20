<?php

class field_Postcode extends Field
{
	public function __construct()
	{
		
	}
	
	public function getDbDefinition()
	{
		return 'int(4) NOT NULL';
	}
}
