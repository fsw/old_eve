<?php

class users_Groups extends Collection
{

  public static function getFields()
  {
 	return array_merge(
 		parent::getFields(), 
 		array(
 			'name' => new field_Email(),
 			'description' => new field_Password(),
 			'members' => new relation_Many('users_Users', 'groups'),
 			'privilages' => new relation_Many('users_Privilages'),
 		)
	);
  }

}
