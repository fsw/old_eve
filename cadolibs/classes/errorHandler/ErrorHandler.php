<?php
/**
 * @package CadoLibs
 * @author fsw
 */

class ErrorHandler
{
	private static $errorNames = array(
			E_ERROR => 'E_ERROR',
			E_WARNING => 'E_WARNING',
			E_PARSE => 'E_PARSE',
			E_NOTICE => 'E_NOTICE',
			E_CORE_ERROR => 'E_CORE_ERROR',
			E_CORE_WARNING => 'E_CORE_WARNING',
			E_CORE_ERROR => 'E_COMPILE_ERROR',
			E_CORE_WARNING => 'E_COMPILE_WARNING',
			E_USER_ERROR => 'E_USER_ERROR',
			E_USER_WARNING => 'E_USER_WARNING',
			E_USER_NOTICE => 'E_USER_NOTICE',
			E_STRICT => 'E_STRICT',
			E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
			E_DEPRECATED => 'E_DEPRECATED',
			E_USER_DEPRECATED => 'E_USER_DEPRECATED',
	);

	private static $callback = null;
	
	public function __construct()
	{
		error_reporting(E_ALL);
		register_shutdown_function(array ($this, 'handleShutdown'));
		set_error_handler(array ($this, 'handleError'));
		set_exception_handler(array($this, 'handleException'));
		ob_start();
	}
	
	public static function setCallback($callback)
	{
		self::$callback = $callback;
	}
	
	public function handleShutdown()
	{
		if (($error = error_get_last()) !== null)
		{
			return $this->handler($error['type'], $error['message'], $error['file'], $error['line'], debug_backtrace(0));
		}
		else
		{
			ob_end_flush();
		}
	}

	public function handleError($code, $message, $file, $line, $context)
	{
		return $this->handler($code, $message, $file, $line, debug_backtrace(0));
	}
	
	public function handleException(Exception $e)
	{
		return $this->handler(E_USER_ERROR, $e->getMessage() . ($e->getCode() ? '(code ' . $e->getCode() . ')' : '') , $e->getFile(), $e->getLine(), $e->getTrace());
	}
	
	private function handler($code, $message, $file, $line, $trace = array())
	{
		if ((self::$callback !== null) && !CADO_DEV)
		{
			call_user_func(self::$callback, $code, $message, $file, $line, $trace);
		}
		if (PHP_SAPI === 'cli')
		{
			echo 'ERROR!';
			var_dump($code, $message, $file, $line, $trace);
			exit;
		}
		if (!empty($_SERVER['SERVER_PROTOCOL']))
		{
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		}
		ob_end_clean();
		$file = str_replace(Cado::$root, '', $file);
		$printDebug = CADO_DEV;
		foreach($trace as &$t)
		{
			if (!empty($t['file']))
			{
				$t['file'] = str_replace(Cado::$root, '', $t['file']);
			}
			if (empty($t['args']))
			{
				$t['args'] = array();
			}
			foreach($t['args'] as &$arg)
			{
				switch (gettype($arg))
				{
					case 'integer':
					case 'double':
						$arg = $arg;
						break;
					case 'string':
						$arg = '"' . $arg . '"';
						break;
					case 'boolean':
						$arg = $arg ? 'true' : 'false';
						break;
					case 'NULL':
						$arg = 'NULL';
						break;
					case 'array':
					case 'object':
					case 'resource':
						ob_start();
						var_dump($arg);
						$arg = '<a title="' . htmlspecialchars(ob_get_clean(), ENT_QUOTES) . '" href="#">' . gettype($arg) . '</a>';
						break;
					case 'unknown type':
					default:
						$arg = 'UNKNOWN';
						break;
				}
			}
		}
		require(Cado::findResource('error.html.php'));
		//Cado::includeResource('error.html.php');
		exit;
	}
}
