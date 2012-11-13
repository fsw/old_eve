<?php

class controller_Index extends controller_Frontend
{
	public function actionIndex()
	{
		$menu = $this->site->model('menus')->getById(module_Cms::getConfig('indexPage'));
		if (!empty($menu['href']))
		{
			$this->redirectTo($menu['href']);
		}
	}
}