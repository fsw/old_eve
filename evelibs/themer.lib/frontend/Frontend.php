<?php

class Frontend extends controller_Layout
{
	/* This controller can be overwritten by site using cms lib */
	public function before($method, $args)
	{
		parent::before($method, $args);
		$this->layout->setHtmlTitle(Config::get('cms', 'siteTitle'));
	}
	
	protected function getLayoutPath()
	{
		return 'theme/' . module_Themer::getConfig('theme') . '/layout';
	}
}