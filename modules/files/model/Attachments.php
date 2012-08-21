<?php
namespace Attachments;

class Attachments extends \Collection
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
