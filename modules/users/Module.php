<?php
namespace Users;

class Module extends \BaseModule
{
	public static function getModel()
	{
		return array('Users', 'Groups', 'Privilages');
	}
}
