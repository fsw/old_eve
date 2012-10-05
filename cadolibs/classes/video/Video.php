<?php
/**
 * 
 * @author fsw 
 *
 */
class Video
{
	public function __construct($path)
	{
		$this->path = $path;
		Fs::isFile($path);
	}
	
	public function saveThumbnail()
	{
		
	}
	
}
