<?php
/**
 * 
 * @author fsw
 *
 */
class Widget
{
	public function __construct($code, $args = array())
	{
	  	$this->__code = $code;
	  	foreach ($args as $key=> $value)
	  	{
	  		$this->$key = $value;
	  	}
	  	$this->__subs = array();
	}
	
	public function toHtml($layout)
	{
		$this->cssPaths = array('/css/static/core.css', '/css/static/fancybox/jquery.fancybox-1.3.4.css', '/css/static/ui/ui-darkness/style.css');
		$this->jsPaths = array('/js/static/jquery.js', '/js/static/fancybox/jquery.fancybox-1.3.4.pack.js', '/js/static/ui/jquery-ui.custom.min.js', '/js/static/core.js');
		$this->cssPaths[] = '/css/layouts/' . $layout . '/default.css';
		$this->jsPaths[] = '/js/layouts/' . $layout . '/script.js';
		$this->cssPaths[] = '/css/widgets/' . $this->__code . '/default.css';
		$this->jsPaths[] = '/js/widgets/' . $this->__code . '/script.js';
		foreach ($this->__subs as $widget)
		{
			$this->cssPaths[] = '/css/widgets/' . $widget . '/default.css';
			$this->jsPaths[] = '/js/widgets/' . $widget . '/script.js';
		}
		$this->htmlTitle = 'TMP';
		ob_start();
		$file = 'core' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'html.html.php';
		$this->__layout = self::findLayoutFile($layout, 'html.php');
		require($file);
		return ob_get_clean();
	}
	
	private function getSearchPaths()
	{
		$paths = array();
		foreach (Autoloader::getSearchPaths() as $path)
		{
			foreach ($this->path as $name)
			{
				$paths[] = $path . DIRECTORY_SEPARATOR . 'widget' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR; 
			}
		}
		return $paths;
	}
	
	public function getCss()
	{
		ob_start();
		//TODO if build!
		foreach ($this->getSearchPaths() as $path)
		{
			$file = $path . $this->skin . '.css';
			if (File::exists($file))
			{
				require($file);
				$found = true;
				break;
			}
		}
		return ob_get_clean();
	}
	
	public function getImg($img)
	{
		ob_start();
		//TODO if build!
		try
		{
			$found = false;
			foreach (Autoloader::getSearchPaths() as $path)
			{
				foreach ($this->path as $name)
				{
					$file = $path . DIRECTORY_SEPARATOR . 'widget' . DIRECTORY_SEPARATOR . $name .
						DIRECTORY_SEPARATOR . $img;
					if (File::exists($file))
				 	{
				 		header('Content-type: image/png');
				 		readfile($file);
				 		exit;
				 	}
				}
			}
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}
		return ob_get_clean();
	}

	private function getCssClass()
	{
		return str_replace('\\', '_', get_class($this)) . '_' . $this->skin;
	}
	
	public static function findLayoutFile($layout, $name)
	{
		foreach (Autoloader::getSearchPaths() as $path)
		{
			$file = $path . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $layout . DIRECTORY_SEPARATOR . $name;
			if (Fs::isFile($file))
			{
				return $file;
			}
		}
		return false;
	}
	
	public static function findFile($code, $name)
	{
		foreach (Autoloader::getSearchPaths() as $path)
		{
			$file = $path . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . $code . DIRECTORY_SEPARATOR . $name;
			if (Fs::isFile($file))
			{
				return $file;
			}
		}
		return false;
	}
	
	public function __toString()
	{
		ob_start();
		//TODO if build!
		try
		{
			if (($path = self::findFile($this->__code, 'html.php')) !== false)
			{
				//echo '<div class="widget ' . $this->getCssClass().'" id="widget'. uniqid() .'">';
				require($path);
				//echo '</div>';
			}
			else
			{
				throw new Exception('Widget "' . $this->__code . '" not found.');
			}
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}
		return ob_get_clean();
	}
	
}
