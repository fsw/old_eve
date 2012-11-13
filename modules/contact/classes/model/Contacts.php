<?php

class model_Contacts extends model_Collection
{
	public function getFields()
	{
		return array_merge(
			parent::getFields(),
			array(
	 			'name' => new field_Text(),
				'email' => new field_Email(),
				//'type' => new field_Enum(array('brochure'=>'Brochure')),
	 			'message' => new field_Longtext(),
			)
		);
	}
	
}
