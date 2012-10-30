<?php

abstract class actions_Frontend extends actions_Layout
{
	protected $layoutName = 'frontend';
	
	public function before($method, $args)
	{
		parent::before($method, $args);
		$this->layout->setHtmlTitle('Demo Website');
		$this->layout->currentUrl = implode('/', $this->request->getPath()) . '.' . $this->request->extension(); 
		$this->layout->mainMenu = $this->site->model('menus')->getMenu('main');
		$this->layout->leftMenu = $this->site->model('menus')->getMenu('left');
		$this->layout->bottomMenu = $this->site->model('menus')->getMenu('bottom');
		$this->layout->addCss('/static/stylesheets/base.css');
		$this->layout->addCss('/static/stylesheets/layout.css');
		$this->layout->addCss('/static/stylesheets/skeleton.css');
		
	}
}