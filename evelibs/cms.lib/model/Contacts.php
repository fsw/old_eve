<?php
/**
 */
class model_Contacts extends model_Set
{
	protected static function initFields()
	{
		return array_merge(
			parent::initFields(),
			array(
	 			'name' => new field_Text(),
				'email' => new field_Email(),
				//'type' => new field_Enum(array('brochure'=>'Brochure')),
	 			'message' => new field_Longtext(),
			)
		);
	}
	
}
