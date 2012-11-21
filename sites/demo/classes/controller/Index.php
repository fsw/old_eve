<?php

class controller_Index extends controller_Frontend
{
	public function actionIndex()
	{
		$this->redirectTo(controller_Content::hrefIndex('home'));
	}
}