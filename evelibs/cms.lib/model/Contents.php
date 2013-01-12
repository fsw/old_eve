<?php

class model_Contents extends model_Set
{
	use model_set_Versioned;
	use model_set_Searchable;
	
	protected static function initFields()
	{
		return array_merge(
			parent::initFields(),
			array(
	 			'title' => new field_Text(),
				'subtitle' => new field_Text(),		
				'modified' => new field_Timestamp(),
				'slug' => new field_Slug('title'),
				'seo_keywords' => new field_Text(),
				'seo_description' => new field_Longtext(),
	 			'body' => new field_Richtext(),
				'files' => new field_Attachments(),		
				'group' => new field_relation_One('groups'),
				'enable' => new field_Bool(),
			)
		);
	}
	
	protected static function initIndexes()
	{
		return array_merge(
			parent::initIndexes(),
			array(
	 			'slug' => array(true, 'slug'),
			)
		);
	}
	
	protected static function beforeSave(&$row)
	{
		if (empty($row['slug']))
		{
			$row['slug'] = empty($row['title']) ? 'content' : Text::slug($row['title']);
		}
		$current = static::getByField('slug', $row['slug']);
		if (!empty($current) && (empty($row['id']) || $current['id'] != $row['id']))
		{
			$currents = static::search('`slug` LIKE ?', array($row['slug'] . '%'));
			$occupiedSlugs = array();
			foreach ($currents as $c)
			{
				$occupiedSlugs[] = $c['slug'];
			}
			$base = $row['slug'];
			$i = 1;
			do
			{
				$row['slug'] = $base . '-' . (++$i);
			} while (in_array($row['slug'], $occupiedSlugs));
		}
	}
}
