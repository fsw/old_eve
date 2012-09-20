<?php

class Actions extends BaseActions
{
	public function actionIndex()
	{
		$this->redirectTo(actions_Content::hrefIndex('home'));
	}
}