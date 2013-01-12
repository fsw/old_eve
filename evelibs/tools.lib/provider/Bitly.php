<?php 

class provider_Bitly
{
	
	public static function getShort($url)
	{
		$ch = curl_init('http://api.bitly.com/v3/shorten?login=francio&apiKey=R_9b2472ecd2ec275f1742547381741e33&longUrl=' . urlencode($url));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		$ret = json_decode($result, true);
		return empty($ret['data']['url']) ? $url : $ret['data']['url'];
	}
		
}
