<?php 

abstract class controller_Layout extends Controller
{
	/**
	 * @var Layout
	 */
	protected $layout = null;
	private $widgetData = array();
	
	public function before($method, $args)
	{
		$path = $this->getLayoutPath();		
		//var_dump($method, $args, $path); die();
		$this->layout = new Layout($path . '.html');
		$this->layout->addJs('/static/jquery.js');
		//$this->layout->addJs('/static/ui/jquery-ui.custom.min.js');
		if (Eve::isDev())
		{
			$this->layout->addJs('/static/jquery/cookie.js');
			$this->layout->addJs('/static/dev.js');
			$this->layout->addJs('/static/RGraph/libraries/RGraph.common.core.js');
			$this->layout->addJs('/static/RGraph/libraries/RGraph.common.dynamic.js');
			$this->layout->addJs('/static/RGraph/libraries/RGraph.common.tooltips.js');
			$this->layout->addJs('/static/RGraph/libraries/RGraph.pie.js');
			$this->layout->addCss('/static/dev.css');
		}
		
		$this->layout->addJs('/static/modernizr.js');
		$this->layout->addJs(Site::lt('layouts', $path, 'js'));
		$this->layout->addCss(Site::lt('layouts', $path, 'css'));
		
		parent::before($method, $args);
	}

	public function after($response)
	{
		if (is_null($response))
		{
			$path = $this->path;
			while ($path && !Template::exists($path . '/' . $this->method . '.html'))
			{
				$path = substr($path, 0, strrpos($path, '/'));
			}
			$response = new Template($path . '/' .  $this->method . '.html', $this->widgetData);
		}
		$this->layout->widget = $response;
		return $this->layout;
	}
	
	public function __set($key, $value)
	{
		$this->$key = $value;
		$this->widgetData[$key] = &$this->$key; 
	}
	
	protected function cacheForever()
	{
		
	}
	
	protected function getLayoutPath()
	{
		$path = $this->path;
		while ($path && !Layout::exists($path . '/layout.html'))
		{
			$path = substr($path, 0, strpos($path, '/'));
		}
		return $path . '/layout';
	}
	
}