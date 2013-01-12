<?php

class module_Cms extends Module
{
	public static function getConfigFields()
	{
		$fields = parent::getConfigFields();
		$fields['indexPage'] = new field_relation_One('menus');

		return $fields;
	}

	public function getModules()
	{
		return array('users');
	}
}
