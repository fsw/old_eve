<?php
namespace Cado;

class Request 
{
  private $path;
  function __construct()
  {
	$this->path = explode('/', $_SERVER['REQUEST_URI']);
	//var_dump($_SERVER);
  }

  function getActionClassName()
  {
	return ''; 
  }
}
 
