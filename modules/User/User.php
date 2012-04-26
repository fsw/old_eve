<?php
namespace User;

class User extends Entity
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




