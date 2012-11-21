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
	
	public function __construct($code)
	{
		$this->code = $code;
		self::$code = $code;
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
	
	public static function readDbStructure()
	{
		$ret = array();
		$tools = new db_Tools(self::getDb());
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
	
	public static function getModels()
	{
		$models = Cado::getDescendants('Model');
		$ret = array();
		foreach ($models as $className)
		{
			$ret[] = lcfirst(str_replace('model_', '', $className));
		}
		return $ret;
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
		Dev::startTimer('unroute');
		$path = explode('_', $class);
		$last = array_pop($path);
		if ($last != 'Index')
		{
			$path[] = lcfirst($last);
		}
		array_shift($path); //remove 'controller_'
		if ($method != 'actionIndex')
		{
			$path[] = lcfirst(substr($method, 6));
		}
		$map = self::getActionsMap();
		
		$pointer =& $map;
		$i = 0;
		while (($i < count($path)) && !empty($pointer['sub']) && array_key_exists($path[$i], $pointer['sub']))
		{
			$pointer =& $pointer['sub'][$path[$i++]];
		}		
		if(empty($pointer['here']))
		{
			throw new Exception('Broken routing');
		}
		
		$defsCount = 0;
		$params = array();
		$extension = 'html';
		
		foreach ($pointer['here']['args'] as $name => $default)
		{
			$value = count($args) ? array_shift($args) : $default;
			if ($name === 'extension')
			{
				$extension = $value;
			}
			elseif ($name === 'ajax')
			{
				//$arg = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
			}
			elseif (strpos($name, 'get') === 0)
			{
				if (!empty($value))
				{
					$params[lcfirst(substr($name, 3))] = $value;
				}
			}
			else
			{
				//TODO null here!
				if ($default == $value)
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
		
		$last = array_pop($path) . '.' . $extension;
		if ($last != 'index.html')
		{
			$path = implode('/', $path) . '/' . $last;
		}
		else 
		{
			$path = empty($path) ? '' : (implode('/', $path) . '/');
		}
		
		$ret = 'http://' . Eve::$domains[0] . '/' . $path . (empty($params) ? '' : '?' . http_build_query($params));
		Dev::stopTimer();
		return $ret;
	}
	
	public function runAction($path, $args)
	{
		$className = 'action_' . implode('_', $path);
		
	}
	
	public static function runTask($code, $args)
	{
		$file = Cado::findResource('tasks/' . $code . '.php');
		if ($file === null)
		{
			throw new Exception('unknown task');
		}
		else
		{
			$task = new Task($code);
			$task->run($args);
			//var_dump($request->getPath());
			//var_dump($request->getPath());
		}
		return null;
	}
	
	public static function getActionsMap()
	{
		$map = cache_Array::get('actionsmap');
		if ($map === null)
		{
			$map = array('sub'=>array());
			foreach (Cado::getDescendants('Controller') as $className)
			{
				$pointer = &$map;
				$path = substr($className, strlen('controller_'));
				foreach (explode('_', $path) as $bit)
				{
					if ($bit != 'Index')
					{
						if (!array_key_exists(lcfirst($bit), $pointer['sub']))
						{
							$pointer['sub'][lcfirst($bit)] = array('sub'=>array());
						}
						$pointer = &$pointer['sub'][lcfirst($bit)];
					}
				}
				$base = array();
				foreach (get_class_methods($className) as $methodName)
				{
					if (strpos($methodName, 'action') === 0)
					{
						$name = substr($methodName, strlen('action'));
						$funcPointer = &$pointer;
						if ($name != 'Index')
						{
							if (!array_key_exists(lcfirst($name), $pointer['sub']))
							{
								$pointer['sub'][lcfirst($name)] = array();
							}
							$funcPointer = &$pointer['sub'][lcfirst($name)];
						}
						
						$funcPointer['here'] = array(
								'class' => $className,
								'method' => $methodName,
								'args' => array());
						
						$reflection = new ReflectionMethod($className, $methodName);
						foreach ($reflection->getParameters() as $param)
						{
							$funcPointer['here']['args'][$param->getName()] = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
						}
								
					}
				}
			}
			cache_Array::set('actionsmap', $map);
		}
		return $map;
	}
	
	public static function getUrlsMap()
	{
		return array();
	}
	
	public static function route(Request $request)
	{
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
			return self::runTask($code, $args);
		}
		
		Dev::startTimer('1_route');
		$map = self::getActionsMap();
		$pointer =& $map;
		while(!empty($pointer['sub']) && array_key_exists($request->glancePath(), $pointer['sub']))
		{
			$pointer =& $pointer['sub'][$request->shiftPath()];
		}
		if (empty($pointer['here']))
		{
			Dev::stopTimer();
			self::show404();
		}
		$className = $pointer['here']['class'];
		$methodName = $pointer['here']['method'];
		$methodCode = lcfirst(substr($methodName, strlen('action')));
		
		$args = array();
		$pathChecked = false;
		$extChecked = false;
		
		foreach ($pointer['here']['args'] as $name => $default)
		{
			if ($name == 'fullpath')
			{
				$value = implode('/', $request->getPath()) . '.' . $request->extension();
				$pathChecked = true;
				$extChecked = true;
			}
			elseif ($name == 'extension')
			{
				$value = $request->extension();
				$extChecked = true;
			}
			elseif ($name == 'referer')
			{
				$value = $request->getReferer();
			}
			elseif (strpos($name, 'get') === 0)
			{
				$value = $request->getParam(lcfirst(substr($name, 3)));
			}
			else
			{
				$value = $request->shiftPath();
			}
			$args[] = $value ?: $default;
		}
		
		
		if ($request->isPathEmpty())
		{
			$pathChecked = true;
		}
		if (!$pathChecked || (!$extChecked && $request->extension() != 'html'))
		{
			//var_dump($pathChecked, $extChecked, $request->extension(), 'asdfasdf');
			Dev::stopTimer();
			self::show404();
		}
		
		Dev::stopTimer();
		
		Dev::startTimer('2_construct');
		$class = new $className($request, $methodName);
		Dev::stopTimer();
		
		Dev::startTimer('3_before');
		$class->before($methodCode, $args);
		Dev::stopTimer();
		
		Dev::startTimer('4_action');
		$response = call_user_func_array(array($class, $methodName), $args);
		Dev::stopTimer();
		
		Dev::startTimer('5_after');
		$response = $class->after($response);
		Dev::stopTimer();
		
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
		echo new Layout('404');
		exit;
	}
	
	public static function redirectTo($url)
	{
		header('Location: ' . $url);
		exit;
	}
}
