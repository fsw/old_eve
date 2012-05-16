<?php

class list_Widget extends Widget
{
	public function __construct(Request &$request)
	{
		$this->active = $request->glancePath();
		
		$this->create = Ticket::validateStructure();
		
		//Ticket::validateStructure();
		
		parent::__construct($request);
	}

	public function prepare($data)
	{
	}	
}
