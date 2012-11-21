<?php 

class module_Users extends Module
{
	public static function requireLogin()
	{
		if (!Site::model('users')->isLoggedIn())
		{
			Site::redirectTo(controller_Users::hrefLogin(Site::getRequest()));
		}
	}
}