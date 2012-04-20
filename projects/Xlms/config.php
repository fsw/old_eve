<?php

class Config {
  
  public static function getDatabaseConnection()
  {
	return array(
	  'host' => 'localhost',
	  'user' => 'cado',
	  'password' => 'cado'
	);
  }
  
  public static function getModules()
  {
	return array('users');
  }
 
}

