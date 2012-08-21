<?php

class users_Module extends BaseModule
{
	public static function getModel()
	{
		return array('Users', 'Groups', 'Privilages');
	}
}
