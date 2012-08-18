<?php
namespace Users;

class Routing extends \BaseRouting
{
	
	protected static function actionLogin()
	{
		return new form_Login();
	}
	
	protected static function actionLogout()
	{
		Users::logout();
		self::redirectTo(\Routing::linkToAction('index'));
	}
}
