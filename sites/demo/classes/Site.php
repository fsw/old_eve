<?php 

class Site extends BaseSite
{
	public function getModules()
	{
		return array('cms', 'contact');
	}
}