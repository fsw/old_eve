<?php

class model_Configs extends model_Set
{
	protected static function initFields()
	{
		return array_merge(
			parent::initFields(),
			array(
	 			'key' => new field_Text(),
				'value' => new field_Array(),
			)
		);
	}
}