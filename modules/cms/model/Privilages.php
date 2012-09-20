<?php

class model_Privilages extends model_Collection
{

	public static function getFields()
	{
		return array_merge(
			parent::getFields(),
			array(
	 			'code' => new field_Text(),
	 			'description' => new field_Longtext(),
			)
		);
	}

}
