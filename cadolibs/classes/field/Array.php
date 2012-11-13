<?php

class field_Array extends field_Longtext
{
	public function toDb($value)
	{
		return json_encode($value);
	}
	
	public function fromDb($cell)
	{
		return json_decode($cell, true);
	}
}
