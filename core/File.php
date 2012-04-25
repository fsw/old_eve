<?php
/**
 * 
 * @author fsw
 *
 */
class File
{
	private $path;

	public function __construct($path)
	{
		$this->$path = $path;
	}

	public function getPath()
	{
		return $this->$path;
	}

	public function setPath($path)
	{
		$this->$path = $path;
	}
	
	static function exists($path)
	{
		return file_exists($path);
	}
	
}