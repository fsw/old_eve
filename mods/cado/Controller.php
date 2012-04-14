<?php
namespace Cado;

class Controller 
{
  static function process(Request $request)
  {
	$className = $request->getActionClassName();

	//return new Response($request);
	//  $path = $r->getActionPath();
	//  require_once('../actions/'. implode('/', $path) . '.php');
  }
}
 
