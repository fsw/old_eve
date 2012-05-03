<?php

class Dir
{
	private $path;

	public function __construct($path)
	{
		$this->path = $path;
	}

	public static function exists($path)
	{
		return file_exists($path);
	}
	
	public function getPath()
	{
		return $this->path;
	}

	public function setPath($path)
	{
		$this->path = $path;
	}

	public function getFiles()
	{

	}

	public function getSubDirs()
	{
		if ($handle = opendir($this->path))
		{
			while (false !== ($entry = readdir($handle)))
			{
				if ($entry != "." && $entry != "..") {
					echo "$entry\n";
				}
			}
			closedir($handle);
		}
	}

}