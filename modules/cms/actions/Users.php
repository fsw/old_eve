<?php

class actions_Users extends BaseActions
{
	protected $layoutName = 'login';
	
	public function actionLogin()
	{
		$form = new Form();
		$form->title = 'Login';
		$form->fields = array(
			'email' => new field_Email(),
			'password' => new field_Password()
		);
		$form->description = '...';
		$form->submitText = 'Login';
		$form->handler = function ($post) {
				$user = (new model_Users($this->db))->login($post['email'], $post['password']);
				if (!empty($user))
				{
					$this->redirectTo(Actions::hrefIndex());
				}
				return array('Wrong email or password');
			};
		return $form;
	}
	
	public function actionLogout()
	{
		users_Users::logout();
		self::redirectTo(Routing::linkToAction('index'));
	}
}
