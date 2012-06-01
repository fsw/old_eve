<?php

class Projects extends TreeCollection
{
	protected static function fields()
	{
		return array_merge(
			parent::fields(),
			array(
				'name' => new field_Text(),
				'description' => new field_Text(),
				'owner' => Users\Users::relationManyToOne(),
			)
		);
	}
}
