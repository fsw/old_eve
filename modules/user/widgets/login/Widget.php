<?php

class layout_Widget extends Widget
{
	public function __construct(Request &$request)
	{
		$action = $request->getParam('action');
		$this->assert(in_array($action, array('list', 'calendar', 'projects')));

		$this->addChild('left', new menu_Widget($request));
		$this->addChild('content', new list_Widget($request));
		$this->addChild('content', new preview_Widget($request));
		parent::__construct($request);
	}
}
