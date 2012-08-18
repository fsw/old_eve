<?php

class Request
{
	private static $params = null;
	private static $path = null;
	private static $subdomain = null;
	private static $post = null;
	private static $project = null;
	private static $href = null;
	
	public static function href($subdomain = array(), $path = array(), $params = array())
	{
		if (CADO_DEV && !empty(self::$project))
		{
			array_unshift($subdomain, self::$project);
		}
		return 'http://' . 
			(empty($subdomain) ? '' : implode('.', $subdomain) . '.') .
			CADO_DOMAIN . '/' . implode('/', $path) . '.html' .
			(empty($params) ? '' : '?' . http_build_query($params));
	}
	
	public static function thisHref()
	{
		self::initVars();
		return self::$href;
	}
	
	public static function getProject()
	{
		self::initVars();
		return self::$project;
	}
	
	public static function getParams()
	{
		self::initVars();
		return self::$params;
	}
	
	public static function getParam($key)
	{	
		self::initVars();
		return isset(self::$params[$key]) ? self::$params[$key] : null; 
	}
	
	public static function getPaths()
	{
		return self::$path;	
	}
	
	public static function getPath($offset)
	{
		self::initVars();
		return isset(self::$path[$offset]) ? self::$path[$offset] : null;	
	}

	public static function shiftPath()
	{
		self::initVars();
		return array_shift(self::$path);
	}

	public static function getSubdomains()
	{
		self::initVars();
		return self::$subdomain;
	}
    
	public static function getSubdomain($offset)
	{
		self::initVars();
		return isset(self::$subdomain[$offset]) ? self::$subdomain[$offset] : null;
	}
	
	public static function shiftSubdomain()
	{
		self::initVars();
		return array_shift(self::$subdomain);
	}
	
	public static function isAjax()
	{
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}
	
	private static function initVars()
	{
		if (self::$params === null)
    	{
			if (PHP_SAPI === 'cli')
			{
				//TODO
				global $argv,$argc;
				array_shift($argv);
				parse_str(implode('&', $argv), $args);
				self::$params = array_merge(self::$params, $args);
			}
			else
			{
				self::$params = $_GET;
				$path = empty($_SERVER['REDIRECT_URL']) ? '' : $_SERVER['REDIRECT_URL'];
				$path = str_replace('.html','', $path);
				self::$path = explode('/', trim($path, '/'));
				$subdomain = str_replace(CADO_DOMAIN, '', $_SERVER['HTTP_HOST']);
				self::$subdomain = array_reverse(explode('.', $subdomain));
				array_shift(self::$subdomain);
				self::$post = $_POST;
				$pageURL = (!empty($_SERVER['HTTPS']) &&  $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
				if ($_SERVER['SERVER_PORT'] != '80')
				{
				    $pageURL .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
				} 
				else 
				{
				    $pageURL .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
				}
				self::$href = $pageURL;
			}
			if (CADO_DEV)
			{
				self::$project = array_shift(self::$subdomain);
			}
    	}
	}
	
}
