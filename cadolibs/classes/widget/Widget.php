<?php
/**
 * 
 * @author fsw 
 *
 */
class Widget extends Template
{
	private $___basePath;
	public function __construct($path)
	{
		$this->___basePath = $path;
		if (Cado::findResource($path))
		{
	  		parent::__construct($path . '/html');
		}
		else
		{
			parent::__construct($path . '.html');
		}
	}
	
	public function getJs()
	{
		//$this->___basePath
	}
	
	public function getCss()
	{
	
	}
	
}
