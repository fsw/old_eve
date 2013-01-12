<?php

class cms_Index extends Cms
{
	public function actionIndex()
	{
		
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
		
		$form->title = $module . ' Config';
		$form->addElements($className::getConfigFields());
		$form->setValues($className::getConfig());
		
		if ($form->validate())
		{
			$data = $form->getValues();
			$ret = $className::saveConfig($data);
			if ($ret == true)
			{
				$this->redirectTo(Site::lt('cms/config/' . $module));
			}
			else
			{
				$form->addErrors($ret);
			}
		}
		return $form;
	}
	
	public function actionErrors()
	{
		$this->errors = model_Errors::getAll();
	}
	
	public function actionMissing()
	{
		$this->missing = model_Missing::get404s();
	}
	
	public function actionCache($clear = null)
	{
		//TODO use tasks here!
		$this->caches = array();
		
		$this->caches['output'] = ['title' => 'Apache output cache'];
		if ($clear === 'output')
		{
			$files = Fs::listFiles(Config::get('site', 'webDir') . '/cache', false, true);
			$dirs = Fs::listDirs(Config::get('site', 'webDir') . '/cache', false, true);
			foreach ($files as $file)
			{
				Fs::remove($file);
			}
			foreach ($dirs as $dir)
			{
				Fs::remove($dir, true);
			}
		}
		$output = Fs::listFiles(Config::get('site', 'webDir') . '/cache', true, true);
		$this->caches['output']['count'] = count($output);
		$size = 0;
		foreach ($output as $file)
		{
			$size += filesize($file);
		}
		$this->caches['output']['size'] = $size;
		
		//APC
		$this->caches['apc'] = ['title' => 'APC (opcode + memory cache)'];
		if ($clear === 'apc')
		{
			apc_clear_cache();
		}
		$apc_info = apc_cache_info();
		$this->caches['apc']['count'] = $apc_info['num_entries'];
		$this->caches['apc']['size'] = $apc_info['mem_size'];
		
		//array
		$this->caches['array'] = ['title' => 'Array cache'];
		if ($clear === 'array')
		{
			
			$files = Fs::listFiles(Eve::getCacheDir() . 'arraycache', false, true);
			$dirs = Fs::listDirs(Eve::getCacheDir() . 'arraycache', false, true);
			foreach ($files as $file)
			{
				Fs::remove($file);
			}
			foreach ($dirs as $dir)
			{
				Fs::remove($dir, true);
			}
		}
		$array = Fs::listFiles(Eve::getCacheDir() . 'arraycache', true, true);
		$this->caches['array']['count'] = count($array);
		$size = 0;
		foreach ($array as $file)
		{
			$size += filesize($file);
		}
		$this->caches['array']['size'] = $size;
	}
	
	public function actionExport()
	{
		/*
		$form = new Form();
		$models = Site::getModels();
		$tables = array();
		foreach ($models as $model)
		{
			$tables = array_merge($tables, Site::model($model)->getStructure());
		}
		foreach (array_keys($tables) as $table)
		{
			$form->addElement(str_replace(Site::getDbPrefix(), '', $table), new field_Bool(true));
		}
		if ($form->validate())
		{
			
		}*/
		return $form;
	}
}
