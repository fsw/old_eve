<?php

class model_Categories extends model_TreeCollection
{
	protected static function getFields()
	{
		return array_merge(
 			parent::getFields(),
			array(
				'name' => new field_Text(),
				'fields' => new field_Longtext(),		
				'icon' => new field_Image(128,128),
			)
		);
	}
}
