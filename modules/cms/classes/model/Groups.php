<?php

class model_Groups extends model_Collection
{

  public function getFields()
  {
 	return array_merge(
 		parent::getFields(), 
 		array(
 			'name' => new field_Text(),
 			'description' => new field_Password(),
 			'privilages' => new field_relation_Many('model_Privilages'),
 		)
	);
  }

}
