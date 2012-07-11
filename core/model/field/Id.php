<?php

class field_Id extends field_Int
{
	public function getDbDefinition()
	{
		return 'int(11) NOT NULL AUTO_INCREMENT';
	}
}
