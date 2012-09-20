<?php

class actions_Cms extends BaseActions
{
	protected $layoutName = 'cms';
	
	public function before($method, $args)
	{
		parent::before($method, $args);
		if (empty($_SESSION['user']))
		{
			$this->redirectTo(actions_Users::hrefLogin());
		}
	}
	
	public function actionIndex()
	{
		return new Widget('rootcms/index');
	}
	
}
