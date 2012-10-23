<?php

abstract class BaseActions
{	
	
	protected $layoutName = null;
	
	/**
	 * @var BaseSite
	 */
	protected $site = null;
	
	/**
	 * @var Request
	 */	
	protected $request = null;
	protected $method = null;
	
	public function __construct(Site $site, Request $request, $method)
	{
		$this->site = $site;
		$this->request = $request;
		$this->method = $method;
	}

	public function getCssFiles()
	{
		//TODO call all actions
		$ret = array();
		if ($file = Cado::findResource('layouts/' . $this->layoutName . '/default.css'))
		{
			$ret[] = $file;
		}
		if ($file = Cado::findResource('layouts/' . $this->layoutName . '.css'))
		{
			$ret[] = $file;
		}
		return $ret;
	}
	
	public function getJsFiles()
	{
		$ret = array();
		if ($file = Cado::findResource('layouts/' . $this->layoutName . '/script.js'))
		{
			$ret[] = $file;
		}
		if ($file = Cado::findResource('layouts/' . $this->layoutName . '.js'))
		{
			$ret[] = $file;
		}
		//TODO call all actions
		return $ret;
	}
	
	public function before($method, $args)
	{
	}
	
	public function after($response)
	{
		return $response;
	}
	
	public function __get($name)
	{
		if ($name === 'widget')
		{
			$this->widget = new Widget('widgets/' . self::getActionsCode($this) . '/' . $this->method);
			return $this->widget;
		}
	}
	
	protected function assert($bool)
	{
		/*
		if (!$condition)
		{
			echo (new widget_404())->toHtml();
			exit;
		}
		*/
	}
	
	public static function getActionsClass($actionsCode)
	{
		$chunks = ($actionsCode == 'index' ? array() : explode('-', $actionsCode));
		array_unshift($chunks, 'actions');
		$chunks[] = ucfirst(array_pop($chunks));
		$className = implode('_', $chunks);
		return Cado::classExists($className) ? $className : null;
	}
	
	public static function getActionsCode($actionsClass)
	{
		$actionsClass = is_string($actionsClass) ? $actionsClass : get_class($actionsClass);
		$chunks = explode('_', $actionsClass);
		$chunks[] = lcfirst(array_pop($chunks));
		array_shift($chunks);
		return empty($chunks) ? 'index' : implode('-', $chunks);
	}
	
	static function __callStatic($method, $arguments)
	{
		if (strpos($method, 'href') === 0)
		{
			$method = 'action' . substr($method, 4); 
			return Site::unroute(get_called_class(), $method, $arguments);
		}
	}
	
	protected function redirectTo($url)
	{
		header('Location: ' . $url);
		exit;
	}
	
}