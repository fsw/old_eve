<?php 

class Theme
{
	
	public static function getConfigFields()
	{
		return array();
	}
	
	private static function menuList(&$ret, $menu, $currentUrl)
	{
		foreach ($menu as $item)
		{
			$ret[] = '<li' . ($item['href'] == $currentUrl ? ' class="current"' : '') . '>';
			$ret[] = '<a href="' . $item['href'] . '">';
			$ret[] = Text::excerpt($item['title'], 32);
			$ret[] = '</a>';
			if (!empty($item['children']))
			{
				$ret[] = '<div class="sub"><ul>';
				$ret[] = self::menuList($ret, $item['children'], $currentUrl);
				$ret[] = '</ul></div>';
			}
			$ret[] = '</li>';
		}
	}
	
	public static function getMenu($code)
	{
		$menu = cache_Array::get('menus/' . $code);
		if ($menu === null)
		{
			$id = module_Themer::getConfig($code);
			$menu = model_Menus::getTree($id, '`enable` = 1 ORDER BY `order` ASC');
			cache_Array::set('menus/' . $code, $menu);
		}
		$ret = ['<ul id="' . $code . '">'];
		self::menuList($ret, $menu, Request::getCurrentPageUrl());
		$ret[] = '</ul>';
		return implode(NL, $ret);
	}
	
}