<?php

class Project extends Entity
{
	public function initStructure()
	{
		$this->name = new field_Line();
		$this->description = new field_Text();
	}
}
