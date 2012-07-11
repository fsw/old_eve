<?php

class Tickets extends TreeCollection
{
	protected static function getFields()
	{
		return array_merge(
 			parent::getFields(),
			array(
				'title' => new field_Text(),
				'description' => new field_Text(),
				'deadline' => new field_Date(),
				'owner' => new relation_ManyToOne(),
			)
		);
	}
}
