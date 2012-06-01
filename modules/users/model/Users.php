<?php
namespace Users;

class Users extends \Collection
{

  public static function getFields()
  {
 	return array(
	  'email' => new field_Email(),
	  'password' => new field_Password(),
	  'name' => new field_Text(),
	  'bio' => new field_LongText(),
	);
  }

}
