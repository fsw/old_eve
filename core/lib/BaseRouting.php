<?php

//use Api;
class BaseRouting
{	
	private static $layout = 'frontend';
	public static $actionName = '';
	public static $params = array();
	
	public static function redirectTo($link)
	{
		header('Location: ' . $link);
		exit; 
	}
	
	private static function assert($condition)
	{
		if (!$condition)
		{
			echo (new widget_404())->toHtml();
			exit;
		}
	}
	
	public static function linkToAction($action)
	{
		$class = get_called_class();
		$p = explode('\\', $class);
		if (count($p) > 1)
		{
			$path = array(lcfirst($p[0]), $action);
		}
		else
		{
			$path = array($action);
		}
		
		$params = array();
		
		$functionName = 'action' . ucfirst($action);
		if (method_exists($class, $functionName))
		{
			$r = new ReflectionMethod($class, $functionName);
			$args = array();
			$i = 0;
			foreach ($r->getParameters() as $param)
			{
				if ($param->getName() == 'referer')
				{
					$params['referer'] = Request::thisHref();
					continue;
				}
				if (++$i < func_num_args())
				{
					$arg = func_get_arg($i);
				}
				else
				{
					$arg = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
				}
				
				if ($param->isArray())
				{
					$params[$param->getName()] = $arg;
				}
				else
				{
    				$path[] = $arg;
				}
			}
		}
		return Request::href(array(), $path, $params);
	}
	
	protected static function setLayout($layout)
	{
		self::$layout = $layout;
	}
	
	private static function getLayout()
	{
		return self::$layout;	
	}
	
	protected static function preAction($action)
	{
	}
	
	protected static function action($action)
	{
		static::preAction($action);
		$functionName = 'action' . ucfirst($action);
		if (method_exists($class = get_called_class(), $functionName))
		{
			static::$actionName = $action;
			$r = new ReflectionMethod($class, $functionName);
			static::$params = $args = array();
			foreach ($r->getParameters() as $param)
			{
				if ($param->isArray() || ($param->getName() == 'referer'))
				{
					static::$params[$param->getName()] = $args[] = Request::getParam($param->getName()) ?: ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
				}
				else
				{
    				static::$params[$param->getName()] = $args[] = Request::shiftPath() ?: ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
				}
			}
			
			$widget = call_user_func_array(array($class, $functionName), $args);
			return $widget;
		}
		throw new Exception('need to implement action:' . $action);
	}
	
	final public static function processRequest()
	{
		switch ($action = Request::shiftPath())
		{
			case 'js':
			case 'img':
			case 'file':
			case 'css':
				$paths = Request::getPaths();
				$path = implode(DIRECTORY_SEPARATOR, $paths);
				array_pop($paths);
				$dir = implode(DIRECTORY_SEPARATOR, $paths);
				$path = Autoloader::findFile($path);
				switch ($action)
				{
					case 'js':
						header('Content-type: text/javascript');
						if (Fs::exists($path))
						{
							$js = Fs::read($path);
							echo $js;
						}
						break;
					case 'img':
					case 'file':
					case 'css':
						header('Content-type: text/css');
						$css = Fs::read($path);
						$css = str_replace('url(\'', 'url(\'/img/' . $dir . '/' , $css);
						//$css = str_replace('url(', 'url(/img/' . $dir . '/' , $css);
						echo $css;
						break;
				}
				exit;
			case 'api':
				break;
			default:
				if (in_array($action, Project::getModules()))
				{
					$className = ucfirst($action) . '\\Routing';
					$action = Request::shiftPath();
					$widget = $className::getWidget($action);
				}
				else
				{
					$widget = static::getWidget($action);
				}
				if ($widget instanceof Widget)
				{
					if (Request::isAjax())
					{
						echo $widget;
					}
					else
					{
						echo $widget->toHtml(static::getLayout());
					}
				}
				elseif (is_scalar($widget) || (is_object($widget) && method_exists($widget, '__toString')))
				{
					echo $widget;
				}
				elseif(is_array($widget))
				{
					echo json_encode($widget);
				}
				elseif(is_null($widget))
				{
					$widget = new Widget('404');
					echo $widget->toHtml('frontend');
				}
				else
				{
					throw new Exception('Unknown object returned from action');
				}
				exit;
		}
		self::assert(false);
	}
	
}
