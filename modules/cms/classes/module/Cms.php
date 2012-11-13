<?php

class module_Cms extends Module
{
	public static function getConfigFields()
	{
		return array_merge(parent::getConfigFields(), array(
			'indexPage' => new field_relation_One('menus'),
		));
	}
	
	public static function getMenu($slug)
	{
		return Site::model('menus')->getTree(Site::getConfig($slug), '`enable` = 1 ORDER BY `order` ASC');
	}
}
