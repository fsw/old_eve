<?php

abstract class BaseSite
{
	
	public function __construct($code)
	{
		$this->code = $code;
		$this->db = new Db();
		$this->db->setPrefix('cado_' . $code);
		//TODO
		//$this->cache = new Cache('path');
	}
	
	public static function factory($siteCode)
	{
		//TODO get modules from site ???
		foreach (Fs::listDirs('modules', false, true) as $moduleDir)
		{
			Cado::addRoot($moduleDir);
		}
		Cado::addRoot('sites' . DS . $siteCode);
		return new Site($siteCode);
	}
	
	public function getModules()
	{
		return array();
	}
	
	public function getCode()
	{
		return Cado::$siteCode;
	}
	
	public function isModuleOn($code)
	{
		return in_array($code, static::getModules());
	}
	
	public static function unroute($class, $method = 'actionIndex', $args = array())
	{
		$path = array(BaseActions::getActionsCode($class)); 
		$path[] = lcfirst(substr($method, 6));
		$params = array();
		$extension = 'html';
		if (method_exists($class, $method))
		{
			$reflection = new ReflectionMethod($class, $method);
			foreach ($reflection->getParameters() as $param)
			{
				$value = count($args) ? array_shift($args) : ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
				if ($param->getName() === 'extension')
				{
					$extension = $value;
				}
				elseif ($param->getName() === 'ajax')
				{
					//$arg = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
				}
				elseif ($param->isArray())
				{
					$params[$param->getName()] = $value;
				}
				else
				{
					$path[] = $value;
				}
			}
		}
		//TODO error on wrong number
		//TODO
		return '/' . implode('/', $path) . '.' . $extension .
		(empty($params) ? '' : '?' . http_build_query($params));;
	}
	
	public function route(Request $request)
	{
		$className = BaseActions::getActionsClass($request->glancePath());
		if ($className === null)
		{
			$className = 'Actions';
		}
		else
		{
			$request->shiftPath();
		}
		$method = method_exists($className, 'action' . ucfirst($request->glancePath())) ? 'action' . ucfirst($request->shiftPath()) : 'actionIndex';
		//TODO cache!
		$reflection = new ReflectionMethod($className, $method);
		$args = array();
		foreach ($reflection->getParameters() as $param)
		{
			if ($param->getName() == 'extension')
			{
				$value = $request->extension();
			}
			elseif ($param->isArray() || ($param->getName() == 'referer'))
			{
				$value = $request->getParam($param->getName());
			}
			else
			{
				$value = $request->shiftPath();
			}
			$args[] = $value ?: ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
		}
		$class = new $className($this, $request);
		$class->before($method, $args);
		$response = call_user_func_array(array($class, $method), $args);
		$response = $class->after($response);
		if (is_scalar($response) || (is_object($response) && method_exists($response, '__toString')))
		{
			return $response;
		}
		elseif(is_array($response))
		{
			return json_encode($response);
		}
		elseif(is_null($response))
		{
			self::show404();
		}
		else
		{
			throw new Exception('Unknown object returned from action');	
		}
	}
	
	public static function show404()
	{
		$layout = new Layout('404');
		echo $layout;
		exit;
	}
}
