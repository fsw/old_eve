<?php
namespace Pages;

class Routing extends \BaseRouting
{
	public static function linkToPage($slug)
	{
		return \Request::href(array(), array('pages', $slug));	
	}
	
	protected static function getWidget($action)
	{
		$page = null;
		if (empty($page))
		{
			return null;
		}
		else
		{
			return new \Widget('page', $page);
		}
	}
}
