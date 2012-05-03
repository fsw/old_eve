<?php

class menu_Widget extends Widget
{
	public function __construct(Request &$request)
	{
		$this->active = $request->glancePath();
		parent::__construct($request);
	}
}
