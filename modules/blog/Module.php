<?php
namespace News;

class Module extends \BaseModule
{
	public static function getModel()
	{
		return array('News');
	}
	
	public static function getModules()
	{
		return array_merge(parent::getModules(), array('attachemnts'));
	}
}
