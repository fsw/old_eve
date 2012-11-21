<?php 

abstract class controller_Layout extends Controller
{
	protected $layoutName = 'frontend';
	/**
	 * @var Layout
	 */
	protected $layout = null;
	private $widgetData = array();
	
	public function before($method, $args)
	{
		$this->layout = new Layout($this->layoutName);
		$this->layout->addJs('/static/jquery.js');
		if (CADO_DEV)
		{
			$this->layout->addJs('/static/jixedbar/src/jquery.jixedbar.js');
			$this->layout->addJs('/static/jquery/cookie.js');
			$this->layout->addJs('/static/dev.js');
			$this->layout->addCss('/static/jixedbar/themes/default/jx.stylesheet.css');
			$this->layout->attachDevbar = true;
		}
		
		$this->layout->addJs('/static/modernizr.js');
		$this->layout->addJs(controller_Static::hrefActions(self::getActionsCode($this), 'js'));
		
		$this->layout->addCss(controller_Static::hrefActions(self::getActionsCode($this), 'css'));
	}

	public function after($response)
	{
		if (is_null($response))
		{
			$response = new Widget($this->getPath() . '/' . $this->method, $this->widgetData);
		}
		$this->layout->widget = $response;
		return $this->layout;
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
	
	public function __set($key, $value)
	{
		$this->widgetData[$key] = $value;
	}
	
	public function __get($key)
	{
		return $this->widgetData[$key];
		/*
		if ($name === 'widget')
		{
			$this->widget = new Widget('widgets/' . self::getActionsCode($this) . '/' . $this->method);
			return $this->widget;
		}*/
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
}