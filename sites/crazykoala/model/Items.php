<?php

class model_Items extends model_Collection
{
	protected static function getFields()
	{
		return array_merge(
 			parent::getFields(),
			array(
					
				'title' => new field_Text(),
				'description' => new field_Longtext(),
					
				'added' => new field_Timestamp(),
							
				'class' => new field_Enum(
					array(
						'product' => 'Product',
						'auction' => 'Auction',
						'adv' => 'Advertisment',
					)),
					
				'category' => new field_relation_One('Categories'),
				'region' => new field_relation_One('Regions'),
			)
		);
	}
}
