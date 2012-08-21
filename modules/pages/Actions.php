<?php

class pages_Actions extends BaseActions
{
	public static function linkToPage($slug)
	{
		return Request::href(array(), array('pages', $slug));	
	}
	
	protected static function action($action, $params = array())
	{
		$page = null;
		if (!empty($page))
		{
			return new Widget('page', $page);
		}
	}
}
