<?php

class Controller implements iController
{
  function controll(Request $request)
  {
	$layout = new Widget('layout');
	switch ($request->shiftPath())
	{
	  case 'admin':
		//return (new controller_Admin())->controll(&$request);
	  case 'index':
		//return new controller_Index()->controll(&$request);
	  case 'ticket':
		//return new controller_Ticket()->controll(&$request);
	}
	$request->unshiftPath();
	return new response_404();
  }
}

