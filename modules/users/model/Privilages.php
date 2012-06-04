<?php
namespace Users;

class Privilages extends \Collection
{

  public static function getFields()
  {
 	return array(
	  'code' => new field_Token(),
	  'description' => new field_(),
	);
  }

}
