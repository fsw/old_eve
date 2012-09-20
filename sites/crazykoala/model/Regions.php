<?php

class model_Regions extends model_TreeCollection
{
	protected static function getFields()
	{
		return array_merge(
 			parent::getFields(),
			array(
				'name' => new field_Text(),
				'postcode' => new field_Postcode(),
				'lat' => new field_Float(),
				'lon' => new field_Float(),
			)
		);
	}
}
