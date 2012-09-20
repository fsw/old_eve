<?php

class Project extends BaseProject
{

	public static function getCode()
	{
		return 'xlms';
	}
	
	public static function getModules()
	{
		return array_merge(parent::getModules(), array('cms'));
	}
	
	public static function getModel()
	{
		return array('Projects', 'Tickets');
	}
	
	public static function moduleCms()
	{
		return array();
	}
	
	public static function install()
	{
		Projects::add(array(
		
		));
	}
	
}
