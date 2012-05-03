<?php

class layout_Widget extends Widget
{
	public function __construct(Request &$request)
	{
		$this->addChild('left', new menu_Widget($request));
		$this->addChild('content', new list_Widget($request));
		$this->addChild('content', new preview_Widget($request));
		parent::__construct($request);
	}
}
