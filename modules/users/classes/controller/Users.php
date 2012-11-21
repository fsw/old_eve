<?php

class controller_Users extends controller_Layout
{
	protected $layoutName = 'users';
	
	public function actionLogin($getRedirectTo)
	{
		$form = new Form();
		$form->title = 'Login Form';
		$form->class = 'login';
		
		$form->addElements(array(
			'email' => array('field' => new field_Email()),
			'password' => array('field' => new field_Password())
		));
		
		if ($form->validate())
		{
			$user = Site::model('users')->login($form->getValue('email'), $form->getValue('password'));
			if (!empty($user))
			{
				$this->redirectTo($getRedirectTo);
			}
			else
			{
				$form->addError('Wrong email or password');
			}
		}
		return $form;
	}
	
	public function actionLogout()
	{
		Site::model('users')->logout();
		$this->redirectTo(controller_Index::hrefIndex());
	}
}
