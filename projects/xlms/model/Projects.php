<?php

class Projects extends TreeCollection
{
	protected static function getFields()
	{
		return array_merge(
 			parent::getFields(), 
			array(
				'name' => new field_Text(),
				'description' => new field_Text(),
				'owner' => new relation_ManyToOne(),
			)
		);
	}
}
