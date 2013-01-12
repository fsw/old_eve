<?php

class model_Missing extends model_Table
{	
	protected static function initFields()
	{
		return array(
				'url' => new field_Text(),				
				'count' => new field_Number(),
		);
	}
	
	protected static function initIndexes()
	{
		return array(
				'primary' => array('url')
		);
	}
	
	public static function add($url)
	{
		static::getDb()->query(
				'INSERT INTO ' . static::getTableName() . 
				' (url, count) VALUES (?,1) ON DUPLICATE KEY UPDATE count=count+1;', [$url]);
	}
	
	public static function getAll()
	{
		return static::getDb()->fetchAll('SELECT * FROM ' . static::getTableName()); 
	}
}