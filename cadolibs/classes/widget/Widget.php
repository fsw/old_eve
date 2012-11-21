<?php
/** 
 * @package CadoLibs
 * @author fsw
 */

class Widget extends Template
{
	public function __construct($path, Array $data = null)
	{
		$path = 'widgets/' . $path;
		if (Cado::findResource($path))
		{
	  		parent::__construct($path . '/html', $data);
		}
		else
		{
			parent::__construct($path . '.html', $data);
		}
	}
}
