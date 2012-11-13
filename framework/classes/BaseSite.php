<?php
/**
 * Site.
 * 
 * @package Framework
 * @author fsw
 */

abstract class BaseSite extends Module
{
	private static $model = array();
	private static $code = null;
	
	private static $db = null;
	private static $instance = null;
	
	public function __construct($code)
	{
		$this->code = $code;
		self::$code = $code;
		self::$instance = $this;
	}
	
	public static function getInstance()
	{
		return static::$instance;
	}
	
	public static function factory($siteCode)
	{
		Cado::addRoot('sites' . DS . $siteCode);
		$site = new Site($siteCode);
		Cado::shiftRoots();
		foreach ($site->getModules() as $module)
		{
			Cado::addRoot('modules' . DS . $module);
		}
		Cado::addRoot('sites' . DS . $siteCode);
		ErrorHandler::setCallback(array($site, 'errorCallback'));
		return $site;
	}
	
	public function errorCallback($code, $message, $file, $line, $trace)
	{
		$this->model('errors')->saveError($code, $message, $file, $line, $trace);
	}
	
	/**
	 * @return Db
	 */
	public static function getDb()
	{
		if (empty(self::$db))
		{
			self::$db = new Db(Eve::$dbConfig);
		}
		return self::$db;	
	}
	
	/**
	 * @return Model 
	 */
	public static function model($code)
	{
		if (empty(self::$model[$code]))
		{
			$className = 'model_' . ucfirst($code);
			self::$model[$code] = new $className(self::getDb(), Eve::$dbConfig['prefix'] . self::$code, array(), self::$model);
		}
		return self::$model[$code];
	}
	
	public function readDbStructure()
	{
		$ret = array();
		$tools = new db_Tools($this->getDb());
		$structure = $tools->getStructure();
		foreach($structure as $name=>$fields)
		{
			if (strpos($name, Eve::$dbConfig['prefix'] . self::$code) === 0)
			{
				$ret[$name] = $fields;
			}
		}
		return $ret;
	}
	
	public static function getModuleCode()
	{
		return 'site';	
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
	
	public static function getCode()
	{
		return Cado::$siteCode;
	}
	
	public static function getDbPrefix()
	{
		return Eve::$dbConfig['prefix'] . self::$code . '_';
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
				elseif (strpos($param->getName(), 'get') === 0)
				{
					if (!empty($value))
					{
						$params[lcfirst(substr($param->getName(), 3))] = $value;
					}
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
		//var_dump($path);
		$last = array_pop($path) . '.' . $extension;
		if ($last != 'index.html')
		{
			$path = implode('/', $path) . '/' . $last;
		}
		else 
		{
			$path = empty($path) ? '' : (implode('/', $path) . '/');
		}
		//var_dump($path, $params);
		return 'http://' . Eve::$domains[0] . '/' . $path .
		(empty($params) ? '' : '?' . http_build_query($params));;
	}
	
	public function runAction($path, $args)
	{
		$className = 'action_' . implode('_', $path);
		
	}
	
	public function runTask($code, $args)
	{
		$file = Cado::findResource('tasks/' . $code . '.php');
		if ($file === null)
		{
			throw new Exception('unknown task');
		}
		else
		{
			$task = new Task($this, $code);
			$task->run($args);
			//var_dump($request->getPath());
			//var_dump($request->getPath());
		}
	}
	
	public static function getActionsMap()
	{
		//TODO array_cache
		$map = array();
		foreach (Cado::getDescendants('BaseActions') as $className)
		{
			$className = array_shift(explode('_', $className));
			
			$base = array();
			foreach (get_class_methods($className) as $method)
			{
				if (strpos($method, 'action') === 0)
				{
					//var_dump($className, $method);
					//$map[]
				}
			}
		}
		return $map;
	}
	
	public static function getUrlsMap()
	{
		return array();
	}
	
	public function route(Request $request)
	{
		//var_dump(self::getActionsMap());
		if ($request->getType() == 'cli')
		{
			$code = $request->shiftPath();
			$args = array();
			while ($bit = $request->shiftPath())
			{
				if (strpos($bit, '=') === false)
				{
					$args[] = $bit;
				}
				else
				{
					$args[substr($bit, 0, strpos($bit, '='))] = substr($bit, strpos($bit, '=') + 1);
				}
			}
			$this->runTask($code, $args);
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
		$methodCode = lcfirst(substr($method, strlen('action')));
		//TODO cache!
		$reflection = new ReflectionMethod($className, $method);
		$args = array();
		foreach ($reflection->getParameters() as $param)
		{
			if ($param->getName() == 'fullpath')
			{
				$value = implode('/', $request->getPath()) . '.' . $request->extension();
			}
			elseif ($param->getName() == 'extension')
			{
				$value = $request->extension();
			}
			elseif ($param->getName() == 'referer')
			{
				$value = $request->getReferer();
			}
			elseif (strpos($param->getName(), 'get') === 0)
			{
				$value = $request->getParam(lcfirst(substr($param->getName(), 3)));
			}
			else
			{
				$value = $request->shiftPath();
			}
			$args[] = $value ?: ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
		}
		$class = new $className($this, $request, $method);
		$class->before($methodCode, $args);
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
