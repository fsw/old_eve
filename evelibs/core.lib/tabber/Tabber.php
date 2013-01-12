<?php
/** 
 * Simple Tabber
 * 
 * @package Core
 * @author fsw
 */

class Tabber extends Template
{
	
	public function __construct($tabs, $current = 0, $icons = null, $template = 'tabber/default.html')
	{
		parent::__construct($template);
		$this->tabs = $tabs; 
  		$this->current = $current;
  		$this->icons = $icons;
	}
	
}
