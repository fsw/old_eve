<?php
/**
 * 
 * @author fsw
 *
 */
class Layout extends Widget
{	
	private $htmlTitle = 'CadoFramework';
	private $htmlDescription = '';
	private $cssUrls = array();
	private $jsUrls = array();
	private $metaProperties = array();
	private $htmlBody = null;
	
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
		$this->htmlBody = parent::__toString();
		ob_start();
		require(Cado::findResource('html.html.php'));
		return ob_get_clean();
	}
	
}
