<?php

class actions_Cms extends actions_Layout
{
	protected $layoutName = 'cms';
	
	public function before($method, $args)
	{
		if (empty($_SESSION['user']))
		{
			$this->redirectTo(
					actions_Users::hrefLogin(
						Site::unroute(get_class($this), $method, $args)
					)
			);
		}
		parent::before($method, $args);
	}
	
	public function actionIndex()
	{
		return new Widget('cms/index');
	}
	
}
