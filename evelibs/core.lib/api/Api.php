<?php 

class Api extends Controller
{
	public static $allowRobots = false;
	
	public function actionIndex($model, $method, $extension, $getArgs)
	{
		if (!is_array($getArgs))
		{
			$getArgs = array();
		}
		$ret = array(
		//				'model' => $model,
		//				'method' => $method,
		//				'args' => $args,
		);
		
		$className = 'model_' . ucfirst($model);
		$this->assert(Eve::classExists($className) && is_subclass_of($className, 'Model'));
		try
		{
			$ret['success'] = true;
			$ret['response'] = call_user_func_array(array($className, $method), $getArgs);
		}
		catch(model_Exception $e)
		{
			$ret['success'] = false;
			$ret['errors'] = $e->getErrors();
		}
		
		if ($extension == 'json')
		{
			$this->setHeader('Content-Type', 'application/json; charset=utf-8');
			return json_encode($ret);
		}
		elseif($extension == 'xml')
		{
			$this->setHeader('Content-Type', 'text/xml; charset=utf-8');
			$xml = new SimpleXMLElement('<root/>');
			array_walk_recursive($ret, array ($xml, 'addChild'));
			return $xml->asXML();
		}
	}
	
}
