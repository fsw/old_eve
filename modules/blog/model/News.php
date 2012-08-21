<?php
namespace News;

class News extends \Collection
{
	public static function getFields()
	{
		return array_merge(
			parent::getFields(),
			array(
	 			'title' => new \field_Text(),
	 			'body' => new \field_Richtext(),
				'postedby' => new relation_One('Users\\Users'),
	 			'attachments' => new \relation_Many('Attachments\\Attachments'),
			)
		);
	}
		
}
