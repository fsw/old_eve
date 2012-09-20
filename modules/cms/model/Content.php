<?php

class model_Content extends model_Collection
{
	public static function getFields()
	{
		return array_merge(
			parent::getFields(),
			array(
	 			'title' => new field_Text(),
				'slug' => new field_Text(),
	 			'body' => new field_Richtext(),
				'postedby' => new field_relation_One('Users\\Users'),
			)
		);
	}
		
}
