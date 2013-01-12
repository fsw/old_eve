<?php
/**
 * All model classes should extend this.
 * 
 * @package Core
 * @author fsw
 */

abstract class Model
{
	private static $db;
		
	/**
	 * @return Db
	 */
	final protected static function getDb()
	{
		if (empty(self::$db))
		{
			self::$db = new Db(Config::get('model', 'db', array()));
		}
		return self::$db;	
	}

	
	final protected static function setPrivilages($privs)
	{
		$_SESSION['model_privilages'] = $privs;
	}
	
	final protected static function clearPrivilages()
	{
		unset($_SESSION['model_privilages']);
	}
	
	final protected static function assertPrivilages($privs)
	{
		foreach ($privs as $key => $value)
		{
			if (!empty($_SESSION['model_privilages'][$key]))
			{
				if (is_array($_SESSION['model_privilages'][$key]))
				{
					foreach ($_SESSION['model_privilages'][$key] as $v)
					{
						if ($v === $value)
						{
							return true;
						}
					}
				}
				elseif ($_SESSION['model_privilages'][$key] == $value)
				{
					return true;
				}
			}
		}
		throw new model_Exception('You dont have sufficient privilages to perform this action');
	}

	
	final protected static function assert($condition, $message)
	{
		if (!$condition)
		{
			throw new model_Exception(array($message));	
		}
	}
	
	final protected static function assertOne($key, $condition, $message)
	{
		if (!$condition)
		{
			static::$errors[$key] = $message;
		}
	}
	
	final protected static function checkAsserts()
	{
		if (!empty(static::$errors))
		{
			throw new model_Exception(static::$errors);
		}
	}
	
	
	final protected static function getBaseName()
	{
		return lcfirst(substr(get_called_class(), strlen('model_')));
	}

	public static function _getDbStructure()
	{
		return array();
	}

}
