<?php

class Project extends BaseProject
{

	public static function getCode()
	{
		return 'meissen';
	}
	
	public static function getModules()
	{
		return array_merge(
			parent::getModules(), 
			array(
				'pages' => array(),
				'users' => array(),
				'cms' => array(),
				'news' => array()
			)
		);
	}
	
	public static function getModel()
	{
		return array_merge(parent::getModel(), array('Projects', 'Tickets'));
	}

}
