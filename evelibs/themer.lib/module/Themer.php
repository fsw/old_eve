<?php

class module_Themer extends Module
{
	public static function getConfigFields()
	{
		$themes = Eve::listDir('theme');
		$options = [];
		foreach ($themes as $theme)
		{
			$options[$theme] = ucfirst($theme);
		}
		$fields = parent::getConfigFields();
		
		$fields['theme'] = new field_Enum($options);
		
		$current = self::getConfig('theme');
		$className = 'theme_' . ucfirst($current);
		
		$fields += $className::getConfigFields();
		
		return $fields;
	}
}

