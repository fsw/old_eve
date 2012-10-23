<?php

class Task
{
	
	private $site;
	private $code;
	
	public function __construct(BaseSite $site, $code)
	{
		$this->code = $code;
		$this->site = $site;
	}
	
	public function run($args)
	{		
		$path = Cado::findResource('tasks/' . $this->code . '.php');
		if ($path)
		{
			include($path);
		}
	}

}
