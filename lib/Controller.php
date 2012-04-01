<?php

class Controller 
{
  static function process(Request $r)
  {
	  $path = $r->getActionPath();
	  require_once('../actions/'. implode('/', $path) . '.php');
  }
}
 
