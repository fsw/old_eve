<?php
/**
 * 
 * @author fsw
 *
 */

class Request
{
	private $path;
	function __construct()
	{
		$this->path = explode('/', $_SERVER['REQUEST_URI']);
		//var_dump($_SERVER);
	}

	function fromUrl($url, $post = array())
	{
		//TODO
	}

	function getPath()
	{
		return '';
	}

	function getDocument()
	{
		return '';
	}
	
	function getParams()
	{
		return '';
	}
	
	function getPostParams()
	{
		return '';
	}
}

