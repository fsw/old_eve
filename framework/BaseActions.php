<?php

abstract class BaseActions
{	
	
	protected $layoutName = null;
	
	public function __construct(Site $site, Request $request)
	{
		$this->site = $site;
		$this->request = $request;
	}

	public function getCssFiles()
	{
		return array(Cado::findResource('layouts/' . $this->layoutName . '/default.css'));
	}
	
	public function getJsFiles()
	{
		return array(Cado::findResource('layouts/' . $this->layoutName . '/default.css'));
	}
	
	public function before($method, $args)
	{
		if ($this->layoutName)
		{
			$this->layout = new Layout($this->layoutName);
			$this->layout->cssUrls[] = actions_Static::hrefActions(BaseActions::getActionsCode($this), 'css');
			$this->layout->jsUrls[] = actions_Static::hrefActions(BaseActions::getActionsCode($this), 'js');
		}
	}
	
	public function after($response)
	{
		if ($this->layoutName)
		{
			$this->layout->widget = $response;
			$response = $this->layout;
		}
		return $response;
	}
	
	public function __get($name)
	{
		if ($name === 'db')
		{
			$this->db = $this->site->db;
			return $this->db;
		}
		elseif ($name === 'widget')
		{
			$this->widget = new Widget('rootcms/dbCheck');
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
		$chunks = explode('-', $actionsCode);
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
		return empty($chunks) ? null : implode('-', $chunks);
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