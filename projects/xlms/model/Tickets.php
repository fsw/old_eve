<?php

class Tickets extends Collection
{
	protected static function getFields()
	{
		return array_merge(
 			parent::getFields(),
			array(
				'title' => new field_Text(),
				'description' => new field_Longtext(),
				'timestamp' => new field_Timestamp(Field::OPTIONAL),		
				'status' => new field_Enum(
					array(
						//OPEN
						'new' => 'New',
						'assigned' => 'Assigned',
						'inprogress' => 'In progress',
						'blocked' => 'Blocked',
						//RESOLVED
						'fixed' => 'Fixed',
						'pushed' => 'Pushed',
						'closed' => 'Closed'
					)),
				'project' => new relation_One('Projects'),
			)
		);
	}
}
