<?php

class model_Errors extends model_Table
{
	protected static $crossSite = true;
	
	protected static function initFields()
	{
		return array_merge(
			parent::initFields(),
			array(
	 			'code' => new field_Number(),
				'message' => new field_Text(),
	 			'file' => new field_Text(),
				'line' => new field_Number(),
				'trace' => new field_Longtext(),
				
				'count' => new field_Number(),		
	 			'url' => new field_Text(),
				'server' => new field_Longtext(),
			)
		);
	}
	
	public function saveError($code, $message, $file, $line, $trace)
	{
		$error = static::searchOne('code = ? AND file = ? AND line =?', array($code, $file, $line));
		if ($error !== null)
		{
			$error['count'] = $error['count'] + 1;
			static::update($error['id'], array('count' => $error['count']));
		}
		else
		{
			$error = array(
				'code' => $code,
				'message' => $message,
				'file' => $file,
				'line' => $line,
				'trace' => json_encode($trace),
				'count' => 1,
				'url' => Request::getCurrentPageUrl(),
				'server' => json_encode($_SERVER),
			);
			static::add($error);
		}
		if ($email = Config::get('dev', 'email'))
		{
			if (in_array($error['count'], array(1, 2, 5, 10, 20, 50, 100, 200, 500, 1000)))
			{
				Email::send(
					$email,
					'Eve Error (' . $error['count'] . '): ' . $error['message'],
					'errorReport', 
					$error
				);
			}
		}
	}
	
	public static function getAll()
	{
		return static::getDb()->fetchAll('SELECT * FROM ' . static::getTableName());
	}
}