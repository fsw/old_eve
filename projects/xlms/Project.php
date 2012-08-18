<?php

class Project extends BaseProject
{

	public static function getCode()
	{
		return 'xlms';
	}
	
	public static function getModules()
	{
		return array_merge(parent::getModules(), array('users', 'cms', 'pages'));
	}
	
	public static function getModel()
	{
		return array_merge(parent::getModel(), array('Projects', 'Tickets'));
	}

}
