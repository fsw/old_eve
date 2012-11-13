<?php

class actions_Cms extends actions_Layout
{
	protected $layoutName = 'cms';
	
	public function before($method, $args)
	{
		parent::before($method, $args);
		if (empty($_SESSION['user']))
		{
			$this->redirectTo(
					actions_Users::hrefLogin(
						Site::unroute(get_class($this), 'action' . ucfirst($method), $args)
					)
			);
		}
		else
		{
			$this->layout->logged = true;
			$modules = array();
			foreach(Cado::getDescendants('Module') as $moduleClass)
			{
				$name = str_replace('module_', '', $moduleClass);
				$modules[lcfirst($name)] = $name;
			}
			$this->layout->modules = $modules;
			
			$this->layout->addJs('/static/tinymce/jscripts/tiny_mce/tiny_mce.js');
			
			$this->layout->addJs('/static/fancybox/jquery.fancybox-1.3.4.pack.js');
			$this->layout->addCss('/static/fancybox/jquery.fancybox-1.3.4.css');
			
			$dataMenu = array();
			$dataActions = Cado::getDescendants('actions_cms_Data');
			foreach ($dataActions as $className)
			{
				if (!in_array($className, array('actions_cms_Users', 'actions_cms_Groups', 'actions_cms_Privilages')))
				{
					$dataMenu[substr($className, strrpos($className, '_') + 1)] = $className::hrefIndex();
				}
			}
			
			$this->layout->dataMenu = $dataMenu;
			$this->layout->user = $_SESSION['user'];
		}
	}
	
	public function actionIndex()
	{
		return new Widget('widgets/cms/index');
	}
	
	public function actionConfig($module)
	{
		$form = new Form();
		
		if ($module == 'site')
		{
			$className = 'Site';
		}
		else
		{
			$className = 'module_' . ucfirst($module);	
		}
		
		$form->addElements($className::getConfigFields());
		$form->setValues($className::getConfig());
		
		if ($form->validate())
		{
			$data = $form->getValues();
			$ret = $className::saveConfig($data);
			if ($ret == true)
			{
				$this->redirectTo($this::hrefConfig($module));
			}
			else
			{
				$form->addErrors($ret);
			}
		}
		return $form;
	}
	
	public function actionCache($clear = null)
	{
		$widget = new Widget('widgets/cms/cache');
		
		if ($clear === 'output')
		{
			//Fs::remove($path, true);
		}
		$output = Fs::listFiles(Cado::$outputCache, true, true);
		$widget->outputCacheCount = count($output);
		$size = 0;
		foreach ($output as $file)
		{
			$size += filesize($file);
		}
		$widget->outputCacheSize = $size;
		if ($clear === 'apc')
		{
			apc_clear_cache();
		}
		$apc_info = apc_cache_info();
		$widget->apcCacheCount = $apc_info['num_entries'];
		$widget->apcCacheSize = $apc_info['mem_size'];
		return $widget;
	}
	
	public function actionExport()
	{
		$form = new Form();
		$models = $this->site->getModels();
		$tables = array();
		foreach ($models as $model)
		{
			$tables = array_merge($tables, $this->site->model($model)->getStructure());
		}
		foreach (array_keys($tables) as $table)
		{
			$form->addElement(str_replace(Site::getDbPrefix(), '', $table), new field_Bool(true));
		}
		if ($form->validate())
		{
			
		}
		return $form;
	}
}
