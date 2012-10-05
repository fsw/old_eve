<?php

abstract class model_KeysCollection extends model_Collection
{
	protected function getFields()
	{
		return array(
			'key' => new field_Token(),
		);
	}
	
  	protected function getIndexes()
  	{
  		return array(
			'primary' => array('key')
		);
  	}
}
