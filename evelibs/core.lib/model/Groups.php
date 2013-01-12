<?php
/**
 */

class model_Groups extends model_Set
{
	protected static function listAllPrivilages()
	{
		$ret = [];
		foreach (Eve::getDescendants('model_Set') as $class)
		{
			$name = str_replace('model_', '', $class);
			$ret['a_' . lcfirst($name)] = 'administrate ' . $name;
			if (isset($class::$moderated))
			{
				$ret['m_' . lcfirst($name)] = 'moderate ' . $name;
			}
		}
		return $ret;
	}
	
	protected static function initFields()
	{
		return array_merge(
				parent::initFields(),
				array(
						'name' => new field_Text(),
						'description' => new field_Longtext(),
						'privilages' => new field_Enum(self::listAllPrivilages(), true),
				)
		);
	}

}
