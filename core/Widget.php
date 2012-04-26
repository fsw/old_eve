<?php
/**
 * 
 * @author fsw
 *
 */
class Widget
{
	private $path;

	public function __construct($path)
	{
	  //if (strpos(':', $path))
	  //{
	//	ltrim($path)
	 // }
	  $this->path = Autoloader::getFileName('widgets\\' . str_replace(':', '', $path) . '\\html');
	}

	public function __set($var, $value)
	{
	  	$this->$var = $value;	
	}

	public function __get($var)
	{
		return $this->$var;
	}
	
	public function appendWidget($layout, $widget)
	{
	}
	
	public function getLayouts()
	{
		
	}

	public function getWidgets($layout)
	{
		
	}
	
	public function render()
	{
		return include($this->path);
	}
	
}
