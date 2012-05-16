<?php

class Ticket extends Entity
{
	protected static function getFields()
	{
		return array(
			'title' => new field_Line(),
			'description' => new field_Text(),
			'deadline' => new field_Date()
		);
	}
}
