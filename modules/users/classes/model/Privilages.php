<?php

class model_Privilages extends model_Collection
{

	protected function initFields()
	{
		return array_merge(
			parent::initFields(),
			array(
	 			'code' => new field_Text(),
	 			'description' => new field_Longtext(),
			)
		);
	}

}
