<?php

abstract class Collection
{
	static $entity;

	static function getAll()
	{
		return Db::fetchAll('SELECT * FROM ' . static::getTableName());
	}

	public static function recreateStructure()
	{
		Db::query('DROP TABLE IF EXISTS ' . static::getTableName());
		Db::query(static::showCreate());
	}

	public static function validateStructure()
	{
		$current = Db::fetchAll('describe ' . static::getTableName());
		$current[] = static::showCreate();
		/*if (count($current))
		{

		}*/
		return $current;
	}

	private static function showCreate()
	{
		$sql[] = '`id` INT';
		/*
		initStructure
		foreach (static::getFields() as $key => $field)
		{
			$sql[] = '`' . $key . '` ' .  $field::getDefinition();
		}*/
		return 'CREATE TABLE ' . static::getTableName() . ' (' . implode(',', $sql) . ')';
	}
}
