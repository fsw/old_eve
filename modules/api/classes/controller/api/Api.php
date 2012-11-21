<?php 

class controller_Api extends Controller
{
	private function call($model, $method, $args)
	{
		if (!is_array($args))
		{
			$args = array();
		}
		$ret = array(
				'model' => $model,
				'method' => $method,
				'args' => $args,
				);
		$object = Site::model($model);
		try
		{
			$ret['response'] = call_user_func_array(array($object, $method), $args);
		}
		catch(model_Exception $e)
		{
			$ret['errors'] = $e->getErrors();
		}
		return $ret;
	}
	
	public function actionXml($model, $method, $getArgs)
	{
		$ret = $this->call($format, $model, $method, $args);
		$xml = new SimpleXMLElement('<root/>');
		array_walk_recursive($ret, array ($xml, 'addChild'));
		return $xml->asXML();
	}
	
	public function actionJson($model, $method, $getArgs)
	{
		return json_encode($this->call($model, $method, $getArgs));
	}
}
