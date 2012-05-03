<?php
namespace Dev\Action;
use Dev;
use Dir;

class Index extends Dev\Action 
{
	static function execute()
	{
		//$request = new Cado\Request();
		$dir = new Dir('projects');
		$projects = $dir->getSubDirs();
		foreach ($projects as $project)
		{
			echo $project->getBaseName();
		}
		//echo Cado\Controller::process(new Cado\Request());
		var_dump($projects);
	}
}