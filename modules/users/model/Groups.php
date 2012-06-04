<?php
namespace Users;

class Groups extends \Collection
{

  public static function getFields()
  {
 	return array(
	  'name' => new field_Email(),
	  'description' => new field_Password(),
	  'users' => Users::relationManyToMany('groups'),
	);
  }

}
