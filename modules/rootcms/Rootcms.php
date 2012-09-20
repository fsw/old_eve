<?php

class Rootcms extends Module
{
	public static $model = array('Users', 'Groups', 'Privilages', 'Attachments'); 
	public static $require = array();
	
	public static function getConfig($key)
	{
		return null;
	}
	
	public static function configFields()
	{
		return array(
				'name' => new field_Email()
			);
	}
}
