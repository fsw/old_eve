<?php 


class google_Maps
{
	public static function staticMap($lat, $lon, $w, $h)
	{
		$url = 'http://maps.googleapis.com/maps/api/staticmap';
		$params = [
			'key' => Config::get('google', 'key'),
			'sensor' => 'false',
			'center' => $lat . ',' . $lon,
			'zoom' => 14,
			'markers' => 'color:blue|label:S|' . $lat . ',' . $lon ,
			'size' => $w . 'x' . $h
		];
		return  $url . '?' . http_build_query($params);
	}
	public static function getAddress($lat, $lon)
	{
		$data = [
		//	'key' => Config::get('google', 'key'),
			'sensor' => Config::get('google', 'sensor'),
			'language' => Config::get('google', 'language'),
			'latlng' => $lat . ',' . $lon
		];
		
		$url = 'http://maps.googleapis.com/maps/api/geocode/json?' . http_build_query($data);
		$json = json_decode(file_get_contents($url), true);
		$ret = array();
		if (!empty($json['status']) && ($json['status'] == 'OK'))
		{
			foreach ($json['results'] as $result)
			{
				foreach ($result['address_components'] as $component)
				{
					foreach ($component['types'] as $type)
					{
						//$ret[$type] = $component['long_name'];
						/*if (in_array($type, ['postal_code_prefix', 'political']))
						{
							break;
						}*/
						if (empty($ret[$type]) || ($ret[$type] == $component['long_name']))
						{
							$ret[$type] = $component['long_name'];
						}
						/*
						else
						{
							$ret[$type] = 'MULTI';
						}*/
					}
				}
			}
		}
		return $ret;
	}
	
	
}