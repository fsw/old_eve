<?php
namespace Api;

class Controller extends \BaseController
{
	static function actionData($model, $method, $args = array())
	{
		if (method_exists($model, $method))
		{
			//TODO check access level
			//$r = new ReflectionMethod($this, $functionName);
			$ret = call_user_func(array($model, $method), $args);
			return $ret;
		}
		else
		{
			throw new \Exception('Unable to call "' . $method . '" method from "' . $model . '" model.');
		}
	}
	
}
