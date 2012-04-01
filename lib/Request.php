<?php

class Request 
{
  static $path;
  function __construct()
  {
	static::$path = array();
  }

  function getActionPath()
  {
	return array('admin');
  }
}
 
