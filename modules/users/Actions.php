<?php

class users_Actions extends BaseRouting
{
	
	protected static function actionLogin()
	{
		return new users_form_Login();
	}
	
	protected static function actionLogout()
	{
		users_Users::logout();
		self::redirectTo(Routing::linkToAction('index'));
	}
}
