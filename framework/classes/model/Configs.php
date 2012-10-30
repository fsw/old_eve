<?php

class model_Configs extends model_Collection
{
	public function getFields()
	{
		return array_merge(
			parent::getFields(),
			array(
	 			'key' => new field_Text(),
				'value' => new field_Longtext(),
			)
		);
	}
}