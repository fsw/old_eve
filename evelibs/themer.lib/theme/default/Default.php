<?php 

class theme_Default extends Theme  {

	public static function getConfigFields()
	{
		return [
			'topMenu' => new field_relation_One('menus'),
			'sideMenu' => new field_relation_One('menus')
		];
	}
}
