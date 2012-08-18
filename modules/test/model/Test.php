<?php
namespace Test;

class Test extends \TreeCollection
{

  protected static function getFields()
  {
 	return array(
	  'date' => new field_Date(),
	  'image' => new field_Image(),
	  'images' => new field_Image2(),
 	  'int' => new field_Int(),
	  'text' => new field_Text(),
	  'string' => new field_String(),
	  'string' => new field_String(),
	);
  }

}
