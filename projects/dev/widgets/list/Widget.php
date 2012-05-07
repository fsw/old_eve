<?php

class list_Widget extends Widget
{
	public function __construct(Request &$request)
	{
		$this->projects = Project::getAll();
		parent::__construct($request);
	}
}
