<?php
/** 
 * @package CadoLibs
 * @author fsw
 */

class Layout extends Template
{	
	private $htmlTitle = 'EveFramework';
	private $htmlDescription = '';
	private $cssUrls = array();
	private $jsUrls = array();
	private $metaProperties = array();
	private $htmlBody = null;
	
	public function __construct($path, Array $data = null)
	{
		$path = 'layouts/' . $path;
		if (Cado::findResource($path))
		{
	  		parent::__construct($path . '/html', $data);
		}
		else
		{
			parent::__construct($path . '.html', $data);
		}
	}
	
	public function setHtmlTitle($htmlTitle)
	{
		$this->htmlTitle = $htmlTitle;
	}
	
	public function setHtmlDescription()
	{
		$this->htmlDescription = $htmlDescription;
	}
	
	public function setMetaProperty($key, $value)
	{
		$this->metaProperties[$key] = $value;
	}
	
	public function addCss($url)
	{
		$this->cssUrls[] = $url;
	}
	
	public function addJs($url)
	{
		$this->jsUrls[] = $url;
	}
	
	public function __toString()
	{
		Dev::startTimer('render');
		try
		{
			$this->htmlBody = parent::__toString();
			ob_start();
			require(Cado::findResource('widgets/html.html.php'));
		}
		catch (Exception $e)
		{
			Cado::handleException($e);
		}
		Dev::stopTimer();
		return ob_get_clean();
	}
	
}
