<?php

abstract class TreeCollection extends Collection
{
	protected static function fields()
	{
		return array_merge(
			parent::fields(),
			array(
				'parent' => static::relationManyToOne(),
			)
		);
	}
}