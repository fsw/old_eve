<?php

abstract class TreeCollection extends Collection
{
	protected static function getFields()
	{
		return array_merge(
			parent::getFields(),
			array(
				'parent' => new relation_ManyToOne(static::getBaseName()),
			)
		);
	}
}
