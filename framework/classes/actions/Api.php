<?php 

class actions_Api extends BaseActions
{
	private function call($model, $method, Array $args)
	{
		return array($model, $method, $args);
	}
	
	public function actionXml($model, $method, Array $args)
	{
		$ret = $this->call($format, $model, $method, $args);
		$xml = new SimpleXMLElement('<root/>');
		array_walk_recursive($ret, array ($xml, 'addChild'));
		return $xml->asXML();
	}
	
	public function actionJson($model, $method, Array $args)
	{
		return json_encode($this->call($model, $method, $args));
	}
}
