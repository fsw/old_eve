<?php
/**
 *
 * @author fsw
 *
 */

class ErrorHandler
{
	public static function init()
	{
		$handler = new ErrorHandler();
		register_shutdown_function(array ($handler, 'handleShutdown'));
		set_error_handler(array ($handler, 'handleError'));
		set_exception_handler(array($handler, 'handleException'));
	}

	public function handleShutdown()
	{
		$error = error_get_last();
		if ($error)
		{
			return $this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
		}
	}

	public function handleError($type, $message, $file, $line)
	{
		var_dump($type, $message, $file, $line);
				exit;
		
		$widget = new Widget('error');
		$widget->number = 404;
		$widget->type = $type;
		$widget->message = $message;
		$widget->file = $file;
		$widget->line = $line;
		$widget->stack = debug_backtrace();
		$widget->render();
	}

	public function handleException(Exception $e)
	{
		return $this->handleError($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
	}
}
