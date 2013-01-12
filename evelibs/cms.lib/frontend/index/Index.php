<?php

class frontend_Index extends Frontend
{
	public function actionIndex()
	{
		if ($id = module_Cms::getConfig('indexPage'))
		{
			$menu = model_Menus::getById($id);
			if (!empty($menu['href']))
			{
				$this->redirectTo($menu['href']);
			}
		}
	}
}