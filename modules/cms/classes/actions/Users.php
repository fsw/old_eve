<?php

class actions_Users extends actions_Frontend
{
	public function actionLogin(Array $redirectTo)
	{
		$form = new Form();
		$form->title = 'Login Form';
		
		$form->setFields(array(
			'email' => new field_Email(),
			'password' => new field_Password()
		));
		
		if ($form->validate())
		{
			$user = $this->site->model('users')->login($form->val('email'), $form->val('password'));
			if (!empty($user))
			{
				$this->redirectTo($redirectTo);
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
		users_Users::logout();
		self::redirectTo(Routing::linkToAction('index'));
	}
}
