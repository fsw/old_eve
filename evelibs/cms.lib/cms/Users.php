<?php

class cms_Users extends controller_Layout
{
	public static $allowRobots = false;
	
	public function before($method, $args)
	{
		parent::before($method, $args);
		$this->layout->logged = false;
	}
	
	public function actionLogin($referer = 'cms')
	{
		$form = new Form();
		$form->title = 'Login to CMS';
		$form->class = 'login';
		
		$form->addElements(array(
			'email' => array('field' => new field_Email()),
			'password' => array('field' => new field_Password())
		));
		
		if ($form->validate())
		{
			$user = model_Users::login($form->getValue('email'), $form->getValue('password'));
			if (!empty($user))
			{
				$this->redirectTo($referer);
			}
			else
			{
				$form->addError('Wrong email or password');
			}
		}
		return $form;
	}

	public function actionForbidden()
	{
		
	}
	
	public function actionLogout()
	{
		model_Users::logout();
		$this->redirectTo(Site::lt('cms'));
	}
	
	public function actionConfirm($getId, $getCode)
	{
		$this->errors = false;
		try
		{
			model_Users::confirmEmail($getId, $getCode);
		}
		catch(model_Exception $e)
		{
			$this->errors = $e->getErrors();
		}
	}
	
	public function actionRegister()
	{
		$form = new Form();
		$form->title = 'Register Form';
		$form->addElements(array(
				'email' => array('field' => new field_Email()),
				'password' => array('field' => new field_Password()),
				'password2' => array('field' => new field_Password()),
				'captcha' => array('field' => new field_Captcha())
		));
		if ($form->validate())
		{
			if ($form->getValue('password') != $form->getValue('password2'))
			{
				$form->addError('passwords differ');
			} 
			else
			{
				try
				{
					model_Users::register(
						$form->getValue('email'),
						$form->getValue('password'),
						$form->getValue('captcha'));
				}
				catch(model_Exception $e)
				{
					$form->addErrors($e->getErrors());
				} 
			}
		}
		return $form;
	}
	
}
