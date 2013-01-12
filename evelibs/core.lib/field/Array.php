<?php

class field_Array extends field_Longtext
{
	public function toDb($value, $key, $code, &$row)
	{
		return json_encode($value);
	}
	
	public function fromDb($cell, $key, $code, &$rowl)
	{
		return json_decode($cell, true);
	}
}
