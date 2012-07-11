<?php

abstract class BaseController
{
	public static function run()
	{
		$action = Routing::getAction();
		$functionName = 'action' . ucfirst($action);		
		if (method_exists(get_called_class(), $functionName))
		{
			$r = new ReflectionMethod(get_called_class(), $functionName);
			$params = $r->getParameters();
			$args = array();
			foreach ($params as $param)
			{
				//$param is an instance of ReflectionParameter
    			$args[] = Routing::get($param->getName()) ?: ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
			}
			call_user_func_array(array(get_called_class(), $functionName), $args);
		}
	}
}
