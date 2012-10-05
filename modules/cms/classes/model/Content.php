<?php

class model_Content extends model_Collection
{
	public function getFields()
	{
		return array_merge(
			parent::getFields(),
			array(
	 			'title' => new field_Text(),
				'slug' => new field_Text(),  //TODO key na to
	 			'body' => new field_Richtext(),
				'group' => new field_relation_One('model_Groups'),
			)
		);
	}
	
	public function getIndexes()
	{
		return array_merge(
			parent::getIndexes(),
			array(
	 			'slug' => array(true, 'slug'),
			)
		);
	}
	
	public function implode(&$row)
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