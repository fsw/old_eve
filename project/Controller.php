<?php
namespace Project;

class Controller extends Cado\Controller
{
  function controll(Cado\Request $request)
  {
	$action = $request->shiftPath();
	switch ($action)
	{
	  case 'index':
		return new Controller_Index($request);
	  case 'ticket':
		return new Controller_Ticket($request); 
	}
	$request->unshiftPath($action);
	
	return new Cado\Response_404();
  }

}

