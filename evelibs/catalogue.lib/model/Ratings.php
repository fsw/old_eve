<?php 
/**
 */
class model_Ratings extends model_Set
{
	protected static function initFields()
	{
		return array_merge(
			parent::initFields(),
			array(
				'item' => new field_relation_One('items'),
	 			'body' => new field_Longtext(),
	 			'rating' => new field_Number(),
	 			'email' => new field_Email(),
			)
		);
	}
}