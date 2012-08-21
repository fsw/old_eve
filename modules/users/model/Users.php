<?php

class users_Users extends Collection
{
	public static function getFields()
	{
		return array_merge(
		parent::getFields(),
		array(
 			'email' => new field_Email(),
 			'password' => new field_Password(),
 			'name' => new field_Text(),
 			'avatar' => new field_Image(),
 			'bio' => new field_Longtext(),
 			'groups' => new relation_Many('users_Groups', 'members'),
		)
		);
	}

	public static function getIndexes()
	{
		return array_merge(
		parent::getIndexes(),
		array(
 			'email' => array(true, 'email'),
		)
		);
	}
	
	public static function getByEmail($email)
	{
		return static::searchOne('email = ?', array($email));
	}
	
	protected static function explode(&$row)
	{
		parent::explode($row);
		$row['title'] = $row['name'] . '(' . $row['email'] . ')';
		return $row;
	}

	public static function register($email)
	{
	
	}
	
	public static function login($email, $password)
	{
		$user = static::getByEmail($email);
		if (!empty($user))
		{
			$_SESSION['user'] = $user;
		}
		return $user;
	}
	
	public static function logout()
	{
		unset($_SESSION['user']);
	}
	
	public static function isLoggedIn()
	{
		return !empty($_SESSION['user']);
	}
	
	public static function getLoggedIn()
	{
		return empty($_SESSION['user']) ? null : $_SESSION['user'];
	}
	
}
