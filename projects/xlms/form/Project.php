<?php

class form_Project extends Form
{

	public function getFields()
	{
		return Projects::fields();
	}
	
	public function handlePost($data)
	{
		$errors = parent::handlePost($data);
		if (empty($errors))
		{
			Projects::save($data);
			Routing::redirectTo(Routing::linkToAction('projects'));
		}
	}
	
}