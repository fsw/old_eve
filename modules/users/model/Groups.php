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
 			'users' => new \relation_ManyToMany('Users'),
 			'privilages' => new \relation_ManyToMany('Privilages'),
 		)
	);
  }

}
