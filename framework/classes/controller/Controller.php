<?php
/**
 * Actions.
 * 
 * @package Framework
 * @author fsw
 */

abstract class Controller
{	
		
	/**
	 * @var Request
	 */	
	protected $request = null;
	protected $method = null;
	
	public function __construct(Request $request, $method)
	{
		$this->request = $request;
		$this->method = $method;
	}


	
	public function before($method, $args)
	{
	}
	
	public function after($response)
	{
		return $response;
	}
	
	protected function assert($condition)
	{
		if (!$condition)
		{
			Site::show404();
		}
	}
	
	public static function getActionsClass($actionsCode)
	{
		$chunks = explode('-', $actionsCode);
		array_unshift($chunks, 'controller');
		$chunks[] = ucfirst(array_pop($chunks));
		$className = implode('_', $chunks);
		return Cado::classExists($className) ? $className : null;
	}
	
	public static function getActionsCode($actionsClass)
	{
		$actionsClass = is_string($actionsClass) ? $actionsClass : get_class($actionsClass);
		$chunks = explode('_', $actionsClass);
		$chunks[] = lcfirst(array_pop($chunks));
		array_shift($chunks); //controller
		return implode('-', $chunks);
	}
	
	protected function getPath()
	{
		$chunks = explode('_', get_class($this));
		$chunks[] = lcfirst(array_pop($chunks));
		array_shift($chunks); //controller
		return implode('/', $chunks);
	}
	
	static function __callStatic($method, $arguments)
	{
		if (strpos($method, 'href') === 0)
		{
			$method = 'action' . substr($method, 4); 
			return Site::unroute(get_called_class(), $method, $arguments);
		}
	}
	
	protected function redirectTo($url)
	{
		header('Location: ' . $url);
		exit;
	}
	
}