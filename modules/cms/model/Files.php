<?php

class model_Files extends model_Collection
{
	public static function getFields()
	{
		return array_merge(
			parent::getFields(),
			array(
	 			'name' => new \field_Text(),
	 			'mime' => new \field_Text(),
	 			'code' => new \field_Token(),
			)
		);
	}
		
}
