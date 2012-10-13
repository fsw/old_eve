<?php

abstract class BaseSite
{
	private $model = array();
	private $db = null;
	
	public function __construct($code)
	{
		$this->code = $code;
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
	
	/**
	 * @return Db
	 */
	public function getDb()
	{
		if (empty($this->db))
		{
			if (defined('CADO_SLAVE_DSN'))
			{
				$this->db = new Db(array(
						array(
							'dsn' => CADO_DB_DSN,
							'user' => CADO_DB_USER,
							'pass' => CADO_DB_PASS,
							'write' => true,
						),
						array(
							'dsn' => CADO_SLAVE_DSN,
							'user' => CADO_SLAVE_USER,
							'pass' => CADO_SLAVE_PASS,
							'write' => false,
						),
				));
			}
			else
			{
				$this->db = new Db(array(
						'dsn' => CADO_DB_DSN,
						'user' => CADO_DB_USER,
						'pass' => CADO_DB_PASS,
				));
			}
			//TODO
			//$this->cache = new Cache('path');
		}
		return $this->db;	
	}
	
	/**
	 * @return Model 
	 */
	public function model($code)
	{
		if (empty($this->model[$code]))
		{
			$className = 'model_' . ucfirst($code);
			$this->model[$code] = new $className($this->getDb(), 'cado_' . $this->code);
		}
		return $this->model[$code];
	}
	
	public function readDbStructure()
	{
		$ret = array();
		$structure = $this->getDb()->getStructure();
		foreach($structure as $name=>$fields)
		{
			if (strpos($name, 'cado_' . $this->code) === 0)
			{
				$ret[$name] = $fields;
			}
		}
		return $ret;
	}
	
	public function getModels()
	{
		$models = Cado::getDescendants('Model');
		$ret = array();
		foreach ($models as $className)
		{
			$ret[] = lcfirst(str_replace('model_', '', $className));
		}
		return $ret;
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
		$path = array(); 
		if (($code = BaseActions::getActionsCode($class)) != 'index')
		{
			$path[] = $code; 
		}
		$path[] = lcfirst(substr($method, 6));
		$defsCount = 0;
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
					if ($param->isDefaultValueAvailable() && ($param->getDefaultValue() == $value))
					{
						$defsCount ++;
					}
					else
					{
						$defsCount = 0;
					}
					$path[] = $value;
				}
			}
			for ($i =0; $i<$defsCount; $i++)
			{
				array_pop($path);
			}
		}
		//TODO error on wrong number
		//TODO
		$last = array_pop($path) . '.' . $extension;
		if ($last != 'index.html')
		{
			$path[] = $last;
		} 
		return 'http://' . CADO_DOMAIN . '/' . implode('/', $path) .
		(empty($params) ? '' : '?' . http_build_query($params));;
	}
	
	public function route(Request $request)
	{
		if ($request->getType() == 'cli')
		{
			$file = Cado::findResource('tasks/' . $request->glancePath() . '.php');
			if ($file === null)
			{
				throw new Exception('unknown task');
			}
			else
			{
				var_dump($request->getPath());
				include($file);
				var_dump($request->getPath());
			}
			return null;
		}
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
			elseif ($param->isArray()) //|| ($param->getName() == 'referer'))
			{
				$value = $request->getParam($param->getName());
				if (!is_array($value))
				{
					$value = array($value);
				}
			}
			else
			{
				$value = $request->shiftPath();
			}
			$args[] = $value ?: ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
		}
		$class = new $className($this, $request, $method);
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
