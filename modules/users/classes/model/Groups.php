<?php

class model_Groups extends model_Collection
{

  protected function initFields()
  {
 	return array_merge(
 		parent::initFields(), 
 		array(
 			'name' => new field_Text(),
 			'description' => new field_Longtext(),
 			'privilages' => new field_relation_Many('privilages'),
 		)
	);
  }

}
