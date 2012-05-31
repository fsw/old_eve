<?php

class Tickets extends TreeCollection
{
	protected static function fields()
	{
		return array_merge(
			parent::fields(),
			array(
				'title' => new field_Text(),
				'description' => new field_Text(),
				'deadline' => new field_Date(),
				'owner' => User\Users::relationManyToOne(),
			)
		);
	}
}
