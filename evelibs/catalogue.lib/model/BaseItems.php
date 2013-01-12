<?php 
abstract class model_BaseItems extends model_Set
{
	use model_set_Moderated;
	
	protected static function initFields()
	{
		return array_merge(
			parent::initFields(),
			array(
	 			'title' => new field_Text(),
	 			'slug' => new field_Slug('title'),
					
	 			'cat1' => new field_relation_One('categories', true),
	 			'cat2' => new field_relation_One('categories', true),
	 			'cat3' => new field_relation_One('categories', true),
					
	 			'locations' => new field_relation_Container('locations', 'item'),

				'rating' => new field_Number(true),
				'rates' => new field_Number(),

				'info' => new field_Longtext(),
				'contact_info' => new field_Longtext(),
					
	 			'phone' => new field_Text(),
	 			'www' => new field_Text(),
	 			'email' => new field_Email(),
	 			'facebook' => new field_Text(),
			)
		);
	}
	
	protected static function explode(&$row)
	{
		parent::explode($row);
		$row['categories_names'] = [];
		$row['categories_plural_genitives'] = [];
		$row['categories'] = [];
		if (!empty($row['cat1']))
		{
			$row['categories'][] = $row['cat1'];
		}
		if (!empty($row['cat2']))
		{
			$row['categories'][] = $row['cat2'];
		}
		if (!empty($row['cat3']))
		{
			$row['categories'][] = $row['cat3'];
		} 
		foreach ($row['categories'] as $category)
		{
			$category = model_Categories::getById($category);
			$row['categories_names'][$category['slug']] = $category['nominative'];
			$row['categories_plural_genitives'][$category['slug']] = $category['plural_genitive'];
		}
		$row['regions_names'] = [];
		$row['regions_locatives'] = [];
		foreach ($row['locations'] as $location)
		{
			if (empty($row['regions_names'][$location['region']]))
			{
				$region = model_Regions::getById($location['region']);
				$row['regions_names'][$region['slug']] = $region['nominative'];
				$row['regions_locatives'][$region['slug']] = $region['locative'];
			}
		}
	}
}