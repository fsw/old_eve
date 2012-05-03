<?php

class Controller implements iController
{
	static function process(Request $request)
	{
		$projects = new Dir('projects');
		$projects = $projects->getSubDirs();
		
		$response = new html_Widget();
		
		$response->addWidget('body', $layout = new widget_Layout());
		$layout->addWidget('menu', new widget_Menu());
				
		switch ($request->shiftPath())
		{
			case null:
				$layout->addWidget('menu', new widget_Menu());
	  		case 'admin':
	  			//return (new controller_Admin())->controll(&$request);
	  		case 'index':
	  			//return new controller_Index()->controll(&$request);
	  		case 'ticket':
	  			//return new controller_Ticket()->controll(&$request);
	  		$layout = new Widget('layout');
		}
		
		return $response;
	}
}

