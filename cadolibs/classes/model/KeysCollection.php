<?php

abstract class model_KeysCollection extends model_Collection
{
	protected function initFields()
	{
		return array(
			'key' => new field_Token(),
		);
	}
	
  	protected function initIndexes()
  	{
  		return array(
			'primary' => array('key')
		);
  	}
}
