<?php
/**
 * 
 * @author fsw
 *
 */
class Widget extends Response
{
	private $path = '';
	private $children = array();

	public function __construct(Request &$request)
	{
	  	$this->path = Autoloader::getFileName(get_called_class());
	}

	public function __set($var, $value)
	{
	  	$this->$var = $value;	
	}

	public function __get($var)
	{
		return $this->$var;
	}
	
	public function addChild($key, $value)
	{
		$this->children[$key][] = $value;
	}
	
	private function echoChildren($name)
	{
		echo empty($this->children[$name]) ? '' : implode('', $this->children[$name]);
	}
	
	public function __toString()
	{
		ob_start();
		include substr($this->path , 0, strlen($this->path) - 10) . 'html.php';
		return ob_get_clean();
	}
	
}
