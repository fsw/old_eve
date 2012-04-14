<?php

class Controller
{
	public static function controll()
	{
	  $request = new Cado\Request();

	  echo Cado\Controller::process(new Cado\Request());
	}
}

