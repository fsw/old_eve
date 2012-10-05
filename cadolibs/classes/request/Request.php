<?php

class Request
{
	private $getParams = array();
	private $postParams = array();
	
	private $domain = '';
	private $pathList = array();
	private $subdomainList = array();
	private $extension = '';
	
	/*
	public static function hrefTo($subdomain = array(), $path = array(), $get = array())
	{
		return 'http://' . 
			(empty($subdomain) ? '' : implode('.', $subdomain) . '.') . 
			CADO_DOMAIN . '/' . implode('/', $path) . (empty($path) ? '' : '.html') . 
			(empty($params) ? '' : '?' . http_build_query($params));
	}*/
	
	public function __construct()
	{
		if (PHP_SAPI === 'cli')
		{
			global $argv,$argc;
			array_shift($argv);
			foreach ($argv as $arg)
			{
					
			}
			parse_str(implode('&', $argv), $args);
			$this->params = array_merge($this->params, $args);
		}
		else
		{
			$this->getParams = $_GET;
			$this->postParams = $_POST;
				
			$path = empty($_SERVER['REDIRECT_URL']) ? '' : $_SERVER['REDIRECT_URL'];
			if (empty($path) || ($path[strlen($path) - 1] === '/'))
			{
				$this->pathList = empty($path) ? array() : explode('/', substr($path, 1, -1));
				$this->pathList[] = 'index';
				$this->extension = 'html';
			}
			elseif (substr($path, -10) === 'index.html')
			{
				//TODO?
				header('Location: http://' . $_SERVER['HTTP_HOST'] . substr($path, 0, -10));
				exit;
			}
			elseif (strrpos($path, '.') <= strrpos($path, '/'))
			{
				//TODO?
				header('Location: http://' . $_SERVER['HTTP_HOST'] . $path . '.html');
				exit;
			}
			else
			{
				$this->extension = substr($path, strrpos($path, '.') + 1);
				$this->pathList = explode('/', substr($path, 1, strrpos($path, '.') - 1));
			}
			/*
			if (empty($path))
			{
				$newPath = '/index.html';
			}
			elseif (strpos($path, '/', strlen($path) - 1) === strlen($path) - 1)
			{
				$newPath = $path . 'index.html';
			}
			elseif (strlen($path) < 6 || strpos($path, '.html',strlen($path) - 5) !== strlen($path) - 5)
			{
				//$newPath = $path . '.html';
			}
			
			if (!empty($newPath))
			{
				header('Location: http://' . $_SERVER['HTTP_HOST'] . $newPath);
				exit;
			}
			$this->pathList = explode('/', substr($path, 1, -5));
			*/
			
			$this->subdomainList = array_reverse(explode('.', $_SERVER['HTTP_HOST']));
			$this->domain = array_shift($this->subdomainList);
			$this->domain = array_shift($this->subdomainList) . '.' . $this->domain;
		}
	}
	
	public static function serialize()
	{
		return array('s' => $this->subdomainStack, 'p' => $this->pathStack, 'g' => $this->getParams);
	}
	
	public function domain()
	{
		return $this->domain;
	}
	
	public function extension()
	{
		return $this->extension;
	}
	
	public function getParams()
	{
		return $this->getParams;
	}
	
	public function getParam()
	{	
		$args = func_get_args();
		$ret =& $this->getParams;
		for ($i = 0; $i < count($args); $i++)
			if (isset($ret[$args[$i]]))
				$ret =& $ret[$args[$i]]; 
			else
				return null;
		return $ret; 
	}
	
	public function postParams()
	{
		return $this->postParams;
	}
	
	public function postParam()
	{
		$args = func_get_args();
		$ret &= $this->postParams;
		for ($i = 0; $i < count($args); $i++)
			if (isset($ret[$args[$i]]))
			$ret &= $ret[$args[$i]];
			else
				return null;
				return $ret;
	}
	
	public function getPath()
	{
		return $this->pathList;	
	}
	
	public function getPathElem($offset)
	{
		return isset($this->pathList[$offset]) ? $this->pathList[$offset] : null;	
	}

	public function shiftPath()
	{
		return array_shift($this->pathList);
	}
	
	public function glancePath()
	{
		return $this->getPathElem(0);
	}

	
	public function getSubdomain()
	{
		return $this->subdomainList;
	}
    
	public function getSubdomainElem($offset)
	{
		return isset($this->subdomainList[$offset]) ? $this->subdomainList[$offset] : null;
	}
	
	public function shiftSubdomain()
	{
		return array_shift($this->subdomainList);
	}
	
	
	public function isAjax()
	{
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}
	
}
