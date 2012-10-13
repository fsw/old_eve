<?php

class actions_Static extends BaseActions
{
	public function actionIndex()
	{
		$path = implode('/', $this->request->getPath()) . '.' . $this->request->extension();
		$filePath = Cado::findResource('static/' . $path);
		if ($filePath !== null)
		{
			//TODO cache will do!
			//var_dump($filePath, Cado::$outputCache . 'static/' . $path);
			Fs::copyr($filePath, Cado::$outputCache . 'static/' . $path);
			if ($this->request->extension() == 'css')
			{
				header('Content-Type: text/css');
			}
			else
			{
				header('Content-Type: ' . Fs::getMime($filePath));
			}
			header('Content-Length: ' . Fs::getSize($filePath));
			return Fs::read($filePath);
		}
	}
	
	public function actionActions($path, $extension)
	{
		//TODO minimize
		$className = BaseActions::getActionsClass($path);
		$actions = new $className($this->site, $this->request, null); 
		switch ($extension)
		{
			case 'js':
				header('Content-type: text/javascript');
				$files = $actions->getJsFiles();
				array_unshift($files, Cado::findResource('static/core.js'));
				foreach ($files as &$file)
				{
					$file = Fs::read($file);
				}
				return implode(NL, $files);
			case 'img':
			case 'file':
			case 'css':
				header('Content-type: text/css');
				$files = $actions->getCssFiles();
				array_unshift($files, Cado::findResource('static/reset.css'));
				foreach ($files as &$file)
				{
					$file = Fs::read($file);
				}
				return implode(NL, $files);
		}
	}
	
}