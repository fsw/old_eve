<?php
/**
 * Versionable collection.
 * 
 * @package Core
 * @author fsw
 */

trait model_set_Versioned
{
	protected static $versioned = true;
	
	final protected static function getRevisionsTableName()
	{ 
		return static::getTableName() . '_revisions';
	}
	
	final protected static function getRevisionsTableStructure($current)
	{
		foreach ($current as $key => $row)
		{
			$current[$key] = str_replace('text NOT NULL', 'text', $current[$key]);
			$current[$key] = str_replace('NOT NULL', 'DEFAULT NULL', $current[$key]);
		}
		$current = ['rev_id' => 'int(11) NOT NULL AUTO_INCREMENT'] + $current;
		$current['id'] = 'int(11) NOT NULL';
		
		$current['index_primary'] = 'PRIMARY KEY (`rev_id`)';
		$current['index_id'] = 'KEY `index_id` (`id`)';
		
		return $current;
	}
	
	final protected static function getRevisions($id)
	{
		return Model::getDb()->fetchAll('SELECT * FROM ' . static::getRevisionsTableStructure() . ' WHERE id=?', [$id]);
	}
}