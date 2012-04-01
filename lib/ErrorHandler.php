<?php

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
	  echo '<h1>sorry. no bonus.</h1>';
	  var_dump($type, $message, $file, $line);
	  /*$message = $errstr . "\n";
      $message .= 'File: ' . $errfile . "\n";
      $message .= 'Line ' . $errline . "\n";
      $message .= '$_SERVER: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : (isset($_SERVER['SCRIPT_URI']) ? $_SERVER['SCRIPT_URI'] : ''));
	  */
	}
    
	public function handleException(Exception $e)
    {
	  return $this->handleError($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine);
    }
}
 
