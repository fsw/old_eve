<?php
/**
 * @package Core
 * @author fsw
 */

class error_Handler
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

	private static $buffer = false;
	
	public function __construct()
	{
		error_reporting(E_ALL);
		set_error_handler(array ($this, 'handleError'));
		set_exception_handler(array($this, 'handleException'));
		register_shutdown_function(array ($this, 'handleShutdown'));
		ob_start();
		static::$buffer = true;
	}
	
	public function handleShutdown()
	{
		if (($error = error_get_last()) !== null) 
		{
			$this->handleException(new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
		}
		elseif(($exception = Eve::stackedException()) !== null)
		{
			$this->handleException($exception);
		}
		else
		{
			if (static::$buffer)
			{
				ob_end_flush();
			}
		}
	}

	public function handleError($errNo, $errStr, $errFile, $errLine, $context)
	{
		throw new ErrorException($errStr, 0, $errNo, $errFile, $errLine);
	}
	
	public function handleException(Exception $exception)
	{
		$saved = true;
		if (!Eve::isDev())
		{
			try {
				if ($exception->getCode() != 404)
				{
					model_Errors::saveError(
						$exception->getCode(), 
						$exception->getMessage(), 
						$exception->getFile(), 
						$exception->getLine(), 
						$exception->getTrace()
					);
				}
				else
				{
					model_Missing::save404(Request::getCurrentPageUrl());
				}
			}
			catch (Exception $e)
			{
				$saved = false;
			}
		}
		if (PHP_SAPI === 'cli')
		{
			echo 'ERROR:' . NL;
			echo $exception->getFile() . ':' . $exception->getLine() . ' ' . $exception->getMessage() . '(' . $exception->getCode() . ')' . NL;
			echo $exception->getTraceAsString() . NL;
			exit;
		}
		while (ob_get_status(true))
		{
			ob_end_clean();
		}
		static::$buffer = false;
		
		if (!empty($_SERVER['SERVER_PROTOCOL']))
		{
			if ($exception->getCode() == 404)
			{
				header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
			}
			else
			{
				header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			}
		}
		header('Content-Type: text/html; charset=utf-8');
		$printDebug = Eve::isDev();
		require(Eve::find('error/template.html.php'));
		exit;
	}
}
