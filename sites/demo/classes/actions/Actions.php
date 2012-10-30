<?php

class Actions extends actions_Frontend
{
	public function actionIndex()
	{
		$this->redirectTo(actions_Content::hrefIndex('home'));
	}
	
	public function actionContact($send = false)
	{
		$form = new Form();
		$form->title = 'Contact us';
		$form->fields = $this->site->model('contacts')->fields();
		if ($form->validate())
		{
			$ret = $this->site->model('contacts')->add($form->getData());
			if ($ret == true)
			{
				$this->redirectTo(Actions::hrefContact('sent'));
			}
			else
			{
				$form->setErrors($ret);
			}
		}
		return $form;
	}
}