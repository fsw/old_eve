<?php

class actions_Users extends actions_Frontend
{
	public function actionLogin(Array $redirectTo)
	{
		$form = new Form();
		$form->title = 'Login Form';
		$form->class = 'login';
		
		$form->setFields(array(
			'email' => new field_Email(),
			'password' => new field_Password()
		));
		
		if ($form->validate())
		{
			$user = $this->site->model('users')->login($form->getField('email'), $form->getField('password'));
			if (!empty($user))
			{
				$this->redirectTo($redirectTo[0]);
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
		$this->site->model('users')->logout();
		$this->redirectTo(Actions::hrefIndex());
	}
}
