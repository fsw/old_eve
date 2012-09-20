<?php

class actions_Content extends BaseActions
{
	protected $layoutName = 'frontend';
	
	public function actionIndex($slug)
	{
		$page = null;
		if (!empty($page))
		{
			return new Widget('page', $page);
		}
	}
}
