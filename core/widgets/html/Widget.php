<?php

class html_Widget extends Widget
{
	private $headers = array();

	public function setHeader($key, $value)
	{
		$this->headers[$key] = $value;
	}

	public function setBody($body)
	{
		$this->body = $body;
	}
	
	public function addChild($key, $value)
	{
		parent::addChild($key, $value);
	}
}

