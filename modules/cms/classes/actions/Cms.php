<?php

class actions_Cms extends actions_Layout
{
	protected $layoutName = 'cms';
	
	public function before($method, $args)
	{
		parent::before($method, $args);
		if (false && empty($_SESSION['user']))
		{
			$this->redirectTo(
					actions_Users::hrefLogin(
						Site::unroute(get_class($this), $method, $args)
					)
			);
		}
		else
		{
			$this->layout->logged = true;
		}
	}
	
	public function actionIndex()
	{
		return new Widget('widgets/cms/index');
	}
	
}
