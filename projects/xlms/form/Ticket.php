<?php

class form_Ticket extends Form
{

	public function getFields()
	{
		return Tickets::fields();
	}
	
	public function handlePost($data)
	{
		$errors = parent::handlePost($data);
		if (empty($errors))
		{
			Tickets::save($data);
			Routing::redirectTo(Routing::linkToAction('index'));
		}
	}
	
}