<?php

abstract class KeysCollection extends Model
{
	protected static function getFields()
	{
		return array(
			'key' => new field_Token(),
		);
	}
	
  	protected static function getIndexes()
  	{
  		return array(
			'primary' => array('key')
		);
  	}
}
