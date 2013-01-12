<?php
/**
 * Actions.
 * 
 * @package Framework
 * @author fsw
 */

abstract class Controller
{	
	public static $allowRobots = true;
	/**
	 * @var Request
	 */	
	protected $request = null;
	protected $method = null;
	protected $path;
	
	private $headers = [];
	
	public function __construct(Request $request, $method)
	{
		$this->request = $request;
		$this->method = $method;
		$this->path = explode('_', get_class($this));
		array_push($this->path, lcfirst(array_pop($this->path)));
		$this->path = implode('/', $this->path);
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
			throw new Exception('Not Found', 404);
		}
	}
	
	protected function setHeader($header, $value)
	{
		$this->headers[$header] = $value;
	}
	
	public function getHeaders()
	{
		return $this->headers;
	}
	
	public static function getActionsClass($actionsCode)
	{
		$chunks = explode('-', $actionsCode);
		array_unshift($chunks, 'controller');
		$chunks[] = ucfirst(array_pop($chunks));
		$className = implode('_', $chunks);
		return Eve::classExists($className) ? $className : null;
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
		die('HAHA');
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