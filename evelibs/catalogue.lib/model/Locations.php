<?php 
/**
*
*/
class model_Locations extends model_Set
{
	protected static function initFields()
	{
		return array_merge(
			parent::initFields(),
			array(
				'item' => new field_relation_One('items'),
					
				'title' => new field_Text(),
	 			
				'lat' => new field_Number(true),
	 			'lon' => new field_Number(true),
				
				'region' => new field_relation_One('regions'),
				
				'postal_code' => new field_Text(),
				'locality' => new field_Text(),
				
				'route' => new field_Text(),
				'street_number' => new field_Number(),
			)
		);
	}
	
	protected static function autocompleteWithGoogle($row)
	{
		if (!empty($row['lat']) && !empty($row['lon']))
		{
			$address = google_Maps::getAddress($row['lat'], $row['lon']);
			$names = [];
			if (!empty($address['administrative_area_level_1']))
			{
				$names[] = $address['administrative_area_level_1'];
			}
			if (!empty($address['administrative_area_level_2']))
			{
				$names[] = $address['administrative_area_level_2'];
			}
			$region_id = model_Regions::getIdForPath($names);
		
			$row['region'] = $region_id;
		}
	}
	
}