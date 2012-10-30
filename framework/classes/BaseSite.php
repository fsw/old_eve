<?php
/**
 * Site.
 * 
 * @package Framework
 * @author fsw
 */

abstract class BaseSite
{
	private $model = array();
	private $db = null;
	public static $site;	//temporary TODO refactor and remove
	
	public function __construct($code)
	{
		$this->code = $code;
	}
	
	public static function factory($siteCode)
	{
		Cado::addRoot('sites' . DS . $siteCode);
		$site = new Site($siteCode);
		Cado::shiftRoots();
		//TODO get modules from site ???
		foreach ($site->getModules() as $module)
		{
			Cado::addRoot('modules' . DS . $module);
		}
		Cado::addRoot('sites' . DS . $siteCode);
		ErrorHandler::setCallback(array($site, 'errorCallback'));
		self::$site = $site;
		return $site;
	}
	
	public function errorCallback($code, $message, $file, $line, $trace)
	{
		$this->model('errors')->saveError($code, $message, $file, $line, $trace);
	}
	
	/**
	 * @return Db
	 */
	public function getDb()
	{
		if (empty($this->db))
		{
			$this->db = new Db(Eve::$dbConfig);
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
			$this->model[$code] = new $className($this->getDb(), 'cado_' . $this->code, array(), $this->model);
		}
		return $this->model[$code];
	}
	
	public function getConfigField($key)
	{
		return null;
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
		return 'http://' . Eve::$domains[0] . '/' . implode('/', $path) .
		(empty($params) ? '' : '?' . http_build_query($params));;
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
	
	public function route(Request $request)
	{
		if ($request->getType() == 'cli')
		{
			$code = $request->shiftPath();
			$args = $request->shiftPath();
			if (!empty($args))
			{
				parse_str($args, $args);
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
				$value = implode('/', $request->getPath()) . $request->extension();
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
