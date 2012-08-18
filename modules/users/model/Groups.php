<?php
namespace Users;

class Groups extends \Collection
{

  public static function getFields()
  {
 	return array_merge(
 		parent::getFields(), 
 		array(
 			'name' => new \field_Email(),
 			'description' => new \field_Password(),
 			'members' => new \relation_Many('Users\\Users', 'groups'),
 			'privilages' => new \relation_Many('Users\\Privilages'),
 		)
	);
  }

}
