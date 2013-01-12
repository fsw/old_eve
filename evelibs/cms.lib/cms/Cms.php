<?php

class Cms extends controller_Layout
{
	public static $allowRobots = false;
	
	public function before($method, $args)
	{
		parent::before($method, $args);		
		if (!model_Users::isLoggedIn())
		{
			$this->redirectTo(Site::lt('cms/users/login'));
		}
		else
		{
			$this->layout->user = model_Users::getLoggedIn();
			if (empty($this->layout->user['privilages']))
			{
				$this->redirectTo(Site::lt('cms/users/forbidden'));
			}
			else
			{
			
				$this->layout->logged = true;
				$modules = array();
				foreach (Eve::getDescendants('Module') as $moduleClass)
				{
					if ($moduleClass::getConfigFields())
					{
						$name = str_replace('module_', '', $moduleClass);
						$modules[lcfirst($name)] = $name;
					}
				}
				$this->layout->modules = $modules;
				
				$this->layout->addJs('/static/tinymce/jscripts/tiny_mce/tiny_mce.js');
				
				$this->layout->addJs('/static/fancybox/jquery.fancybox-1.3.4.pack.js');
				$this->layout->addCss('/static/fancybox/jquery.fancybox-1.3.4.css');
				
				$dataMenu = array();
				$dataActions = Eve::getDescendants('cms_Data');
				foreach ($dataActions as $className)
				{
					if (!in_array($className, array('cms_data_Users', 'cms_data_Groups')))
					{
						$name = explode('_', $className);
						$name = array_pop($name);
						$dataMenu[$name] = Site::lt('cms/data/' . lcfirst($name));
					}
				}
				
				$this->layout->dataMenu = $dataMenu;
			}
		}
	}
	
}
