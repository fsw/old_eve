<?php

class field_Bool extends Field
{	
	public function getDbDefinition()
	{
		return 'tinyint(1) NOT NULL';
	}
}
