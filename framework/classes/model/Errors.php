<?php

class model_Errors extends model_Collection
{
	protected function initFields()
	{
		return array_merge(
			parent::initFields(),
			array(
	 			'code' => new field_Int(),
				'message' => new field_Text(),
	 			'file' => new field_Text(),
				'line' => new field_Int(),
				'trace' => new field_Longtext(),
				
				'count' => new field_Int(),		
	 			'url' => new field_Text(),
				'server' => new field_Longtext(),
			)
		);
	}
	
	public function saveError($code, $message, $file, $line, $trace)
	{
		//var_dump($code, $message, $file, $line, $trace);
		//die('OK');
		$error = $this->searchOne('code = ? AND file = ? AND line =?', array($code, $file, $line));
		if ($error !== null)
		{
			$error['count'] = $error['count'] + 1;
			$this->update($error['id'], array('count' => $error['count']));
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
			$this->add($error);
		}
		if (!empty(Eve::$devEmail))
		{
			if (in_array($error['count'], array(1,10,100,1000)))
			{
				Mail::send(
					Eve::$devEmail,
					'Eve Error [' . $error['count'] . ']: ' . $error['message'],
					'errorReport', 
					$error
				);
			}
		}
	}
}