<?php

class field_Slug extends field_Text
{
	public function __construct($origin)
	{
		$this->origin = $origin;
		parent::__construct();
	}
	
	public function getLoremIpsum()
	{
		return uniqid('slug_');
	}
}
