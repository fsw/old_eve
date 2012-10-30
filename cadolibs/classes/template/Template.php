<?php
/**
 * @package CadoLibs
 * @author fsw
 */

class Template
{
	protected $____path = null;
	protected $____data = array();
	
	public function __construct($path, Array $data = null)
	{
	  	$this->____path = $path;
	  	if (!empty($data))
	  	{
	  		$this->____data = $data;
	  	}
	}
	
	public function __set($key, $value)
	{
		$this->____data[$key] = $value;
	}
	
	private static function quote($var)
	{
		if (is_string($var))
		{
			return htmlspecialchars($var, ENT_COMPAT);
		}
		elseif (is_array($var))
		{
			foreach($var as &$val)
			{
				$val = self::quote($val);
			}
			return $var;
		}
		else
		{
			return $var;
		}
	}
	
	public function __get($key)
	{
		return !isset($this->____data[$key]) ? null : self::quote($this->____data[$key]);
	}
	
	public function unsecured($key)
	{
		return !isset($this->____data[$key]) ? null : $this->____data[$key];
	}
	
	public function __toString()
	{
		Dev::startTimer('render');
		try
		{
			if ($path = Cado::findResource($this->____path . '.php'))
			{
				ob_start();
				require($path);
				$ret = ob_get_clean();
			}
			elseif ($path = Cado::findResource($this->____path))
			{
				$ret = Fs::read($path);
			}
			else
			{
				throw new Exception('Template "' . $this->____path . '" not found.');
			}
		}
		catch (Exception $e)
		{
			Cado::handleException($e);
		}
		Dev::stopTimer();
		return $ret;
	}
	
}
