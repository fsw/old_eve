<?php

class actions_Contact extends actions_Frontend
{	
	public function actionIndex()
	{
		$form = new Form();
		$form->title = 'Contact us';
		$fields = $this->site->model('contacts')->fields();
		unset($fields['id']);
		$form->addElements($fields);
		if ($form->validate())
		{
			$ret = $this->site->model('contacts')->add($form->getValues());
			if ($ret == true)
			{
				$this->redirectTo(actions_Contact::hrefSent());
			}
			else
			{
				$form->setErrors($ret);
			}
		}
		return $form;
	}
	
	public function actionSent($send = false)
	{
		return 'Thank you';
	}
}