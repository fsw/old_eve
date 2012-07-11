<?php
/**
 * 
 * @author fsw
 *
 */
class Widget
{
	private $path = '';

	public function __construct($path)
	{
	  	$this->path = $path;
	}

	public function __set($var, $value)
	{
	  	$this->$var = $value;	
	}

	public function __get($var)
	{
		return $this->$var;
	}
		
	public function subWidget($path)
	{
		echo new Widget($path);
	}
		
	public function __toString()
	{
		ob_start();
		//TODO if build!	
		foreach (Autoloader::getSerachPaths() as $path)
		{
			$file = $path . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . $this->path;
		 	if (File::exists($file . '.html.php'))
		 	{
		 		require($file . '.html.php');
		 		break;
		 	}
		 	elseif (File::exists($file . DIRECTORY_SEPARATOR . 'html.php'))
		 	{
		 		require($file . DIRECTORY_SEPARATOR . 'html.php');
		 		break;
		 	}
		}
		//TODO error
		return ob_get_clean();
	}
	
}
