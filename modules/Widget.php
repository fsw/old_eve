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
		$path = explode(':', $path);
		$this->$path = $path;
	}

	public function __set($var, $value)
	{
		
	}

	public function __get($var)
	{
		
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
		return include($path);
	}
	
}