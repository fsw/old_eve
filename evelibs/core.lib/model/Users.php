<?php

class model_Users extends model_Set
{
	protected static function initFields()
	{
		return array_merge(
			parent::initFields(),
			array(
	 			'email' => new field_Email(),
	 			'password' => new field_Password(),
	 			'confirmed' => new field_Bool(),
				'groups' => new field_relation_Many('groups'),
			)
		);
	}

	protected static function initIndexes()
	{
		return array_merge(
			parent::initIndexes(),
			array(
	 			'email' => array(true, 'email'),
			)
		);
	}
		
	public static function getByEmail($email)
	{
		return static::searchOne('email = ?', array($email));
	}
	
	public static function resetPassword($email)
	{
		
	}
	
	public static function confirmEmail($id, $code)
	{
		$salt = Config::get('crypt', 'salt');
		$user = static::getById($id);		
		if (!empty($user) && ($code == md5($user['email'] . Config::get('crypt', 'salt'))))
		{
			static::update($id, ['confirmed' => true]);
		}
		else 
		{
			throw new model_Exception('Wrong code');
		}
	}
	
	public static function register($email, $password, $captcha)
	{
		$user = static::getByEmail($email);
		if (empty($user))
		{
			if (!Captcha::isValid($captcha))
			{
				throw new model_Exception(['Invalid captcha code']);
			}
			
			$id = static::add([
				'email' => $email,
				'password' => $password,
				'confirmed' => false
			]);
			//TODO
			Email::send($email, 'Confirm your email', 'register', ['url' => Site::lt('cms/users/confirm', $id, md5($email . Config::get('crypt', 'salt')))]);
			return $id;
		}
		else
		{
			throw new model_Exception('This email is already registered');
		}
	}
	
	public static function registerAnonymous($email)
	{
		static::add(array('email'=>$email, 'password'=>$password));
	}
	
	public static function getOrRegister($email)
	{
		$user = static::getByEmail($email);
		if (empty($user))
		{
			$user = static::registerAnonymous($email);
		}
		return $user;
	}
	
	public static function login($email, $password)
	{
		$user = static::getByEmail($email);
		if (!empty($user) && ($user['password'] == md5($password)))
		{
			$user['groups'] = model_Groups::getByIds($user['groups']);
			$privs = array();
			foreach ($user['groups'] as $group)
			{
				$privs = array_merge($privs, $group['privilages']);
			}
			$user['privilages'] = $privs;
			if ($user['id'] == 1)
			{
				//root user
				$user['privilages'] = array_keys(model_Groups::getField('privilages')->values);
			}
			$_SESSION[static::getBaseName() . '_logged'] = $user;
			return $user;
		}
		return false;
	}
	
	public static function logout()
	{
		unset($_SESSION[static::getBaseName() . '_logged']);
	}
	
	public static function isLoggedIn()
	{
		return !empty($_SESSION[static::getBaseName() . '_logged']);
	}
	
	public static function getLoggedIn()
	{
		return empty($_SESSION[static::getBaseName() . '_logged']) ? null : $_SESSION[static::getBaseName() . '_logged'];
	}
	
	public static function getLoggedInId()
	{
		return empty($_SESSION[static::getBaseName() . '_logged']) ? null : $_SESSION[static::getBaseName() . '_logged']['id'];
	}
	
	public static function getLoggedInGroupsIds()
	{
		if (empty($_SESSION[static::getBaseName() . '_logged']))
		{
			return null; 
		}
		else
		{
			$ret = [];
			foreach ($_SESSION[static::getBaseName() . '_logged']['groups'] as $g)
			{
				$ret[] = $g['id'];
			}
			return $ret;
		}
	}
	
	public static function getLoggedInPrivilages()
	{
		return empty($_SESSION[static::getBaseName() . '_logged']) ? [] : $_SESSION[static::getBaseName() . '_logged']['privilages'];
	}
	
	
}
