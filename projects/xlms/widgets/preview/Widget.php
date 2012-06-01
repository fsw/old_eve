<?php

class preview_Widget extends Widget
{
	public function __construct(Request &$request)
	{
		$this->active = $request->glancePath();
		/*
		$ticket = new Ticket();
		$ticket->setTitle('test ticket');
		$ticket->setDescription('test description');
		$ticket->save();

		//$this->ticket = Ticket::getById(1);

		$this->ticketId = $ticket->getId();
		*/
		parent::__construct($request);
	}
}
