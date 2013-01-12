<?php

class frontend_Contact extends Frontend
{	
	static public function sitemapIndex()
	{
		return [[[]]];
	}
	
	public function actionIndex()
	{
		$form = new Form();
		$form->title = 'Contact us';
		$fields = model_Contacts::getFields();
		unset($fields['id']);
		$form->addElements($fields);
		if ($form->validate())
		{
			$ret = model_Contacts::add($form->getValues());
			if ($ret == true)
			{
				$this->redirectTo(controller_Contact::hrefSent());
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