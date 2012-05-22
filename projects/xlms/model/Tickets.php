<?php

class Tickets extends Collection
{
	protected static function structure()
	{
		return array(
			'title' => new field_Input(),
			'description' => new field_LongText(),
			'deadline' => new field_Date()
		);
	}

	private static function indexes()
	{

	}
}
