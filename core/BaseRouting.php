<?php

class BaseRouting
{
	private static $params = null;
	private static $pathParams = null;
	private static $domainParams = null;
	
	protected static function inPathParams()
	{
		return array('Action');
	}

	protected static function inDomainParams()
	{
		return array();
	}
	
	public static function getController()
	{
		if (PHP_SAPI === 'cli')
		{
			return new Api\Controller();
		}
		return new Controller();
	}
    	
	public static function get($param)
	{
		self::preapareParams();
        if (!array_key_exists($param, self::$params))
        {
        	if (in_array($param, static::inPathParams()))
        	{
        		self::$params[$param] = array_shift(self::$pathParams);
        	}
        	elseif (in_array($param, static::inDomainParams()))
        	{
        		self::$params[$param] = array_shift(self::$domainParams);
        	}
        	else
        	{
        		self::$params[$param] = null;
        	}
        }
        return self::$params[$param];
	}
	
    public static function __callStatic($name, $arguments)
    {
        if (strpos($name, 'get') === 0)
        {
        	return self::get(substr($name, 3));
        }
    }
	
    private static function link($params)
    {
    	return '';
    }
    
	private static function preapareParams()
	{
		if (self::$params == null)
    	{
			if (PHP_SAPI === 'cli')
			{
				global $argv,$argc;
				array_shift($argv);
				self::$params['Action'] = array_shift($argv);
				parse_str(implode('&', $argv), $args);
				self::$params = array_merge(self::$params, $args);
			}
			else
			{
				self::$params = $_GET;
				self::$pathParams = explode('/', $_SERVER['REQUEST_URI']);
				self::$domainParams = explode('.', $_SERVER['HTTP_HOST']);
			}
    	}
	}
	
}
