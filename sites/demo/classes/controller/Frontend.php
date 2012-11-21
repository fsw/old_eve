<?php

abstract class controller_Frontend extends controller_Layout
{
	protected $layoutName = 'frontend';
	
	public function before($method, $args)
	{
		parent::before($method, $args);
		$this->layout->setHtmlTitle('Demo Website');
		$this->layout->currentUrl = implode('/', $this->request->getPath()) . '.' . $this->request->extension(); 
		$this->layout->mainMenu = Site::model('menus')->getMenu('main');
		$this->layout->leftMenu = Site::model('menus')->getMenu('left');
		$this->layout->bottomMenu = Site::model('menus')->getMenu('bottom');
		$this->layout->addCss('/static/stylesheets/base.css');
		$this->layout->addCss('/static/stylesheets/layout.css');
		$this->layout->addCss('/static/stylesheets/skeleton.css');
		
	}
}