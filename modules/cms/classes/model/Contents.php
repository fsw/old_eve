<?php

class model_Contents extends model_Collection
{
	protected function initFields()
	{
		return array_merge(
			parent::initFields(),
			array(
	 			'title' => new field_Text(),
				'subtitle' => new field_Text(),		
				'slug' => new field_Text(),
				'seo_keywords' => new field_Text(),
				'seo_description' => new field_Longtext(),
	 			'body' => new field_Richtext(),
				'group' => new field_relation_One('groups'),
				'enable' => new field_Bool(),
			)
		);
	}
	
	public function initIndexes()
	{
		return array_merge(
			parent::initIndexes(),
			array(
	 			'slug' => array(true, 'slug'),
			)
		);
	}
	
	protected function beforeSave(&$row)
	{
		if (empty($row['slug']))
		{
			$row['slug'] = empty($row['title']) ? 'content' : Text::slug($row['title']);
		}
		$current = $this->getByField('slug', $row['slug']);
		if (!empty($current) && (empty($row['id']) || $current['id'] != $row['id']))
		{
			$currents = $this->search('`slug` LIKE ?', array($row['slug'] . '%'));
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
