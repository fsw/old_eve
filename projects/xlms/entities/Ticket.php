<?php

class Ticket extends Entity
{	
	public function initStructure()
	{
		$this->title = new field_Line();
		$this->description = new field_Text();
		$this->deadline = new field_Date();
	}
}
