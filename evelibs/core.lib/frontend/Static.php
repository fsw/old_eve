<?php

class frontend_Static extends Controller
{
	public function actionIndex($fullpath)
	{
		$filePath = Eve::find('static/' . $fullpath);
		if ($filePath !== null)
		{				
			//TODO cache will do!
			//var_dump($filePath, Eve::$outputCache . 'static/' . $path);
			Fs::copyr($filePath, Config::get('site', 'webDir') . '/cache/static/' . $fullpath);
			if ($this->request->extension() == 'css')
			{
				$this->setHeader('Content-Type', 'text/css');
			}
			else
			{
				$this->setHeader('Content-Type', Fs::getMime($filePath));
			}
			$this->setHeader('Content-Length', Fs::getSize($filePath));
			return Fs::read($filePath);
		}
	}
}