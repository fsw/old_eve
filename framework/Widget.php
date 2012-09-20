<?php
/**
 * 
 * @author fsw 
 *
 */
class Widget
{	
	protected $templatesPath = 'widgets/';
	
	public function __construct($path, $data = null)
	{
	  	$this->path = $path;
	  	$this->data = $data;
	}
		
	public function __toString()
	{
		try
		{
			$path = Cado::findResource($this->templatesPath . $this->path . '.html.php');
			if ($path === null)
			{
				$path = Cado::findResource($this->templatesPath . $this->path . '/html.php');
			}
			if ($path !== null)
			{
				ob_start();
				require($path);
				return ob_get_clean();
			}
		}
		catch (Exception $e)
		{
			trigger_error($e->getMessage());
		}
		trigger_error('Widget "' . $this->path . '" not found.');
	}
	
}
