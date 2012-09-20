<?php

class model_Groups extends model_Collection
{

  public static function getFields()
  {
 	return array_merge(
 		parent::getFields(), 
 		array(
 			'name' => new field_Email(),
 			'description' => new field_Password(),
 			'members' => new field_relation_Many('users_Users', 'groups'),
 			'privilages' => new field_relation_Many('users_Privilages'),
 		)
	);
  }

}
