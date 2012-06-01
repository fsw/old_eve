<?php

class list_Widget extends Widget
{
	public function __construct(Request &$request)
	{
		$this->tickets = Tickets::getAll();
		parent::__construct($request);
	}
}
