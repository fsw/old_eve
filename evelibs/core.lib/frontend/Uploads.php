<?php

class frontend_Uploads extends Controller
{
	public static function sitemapIndex()
	{
		$ret = [];
		/*
		foreach (model_Files::getAll() as $file)
		{
			if ($file['file']['type'] == 'pdf')
			{
				$path = $file['file']['name'] . '.' . $file['file']['type'];
				if (file_exists(Site::getDataDir() . $path))
				{
					$lastmod = filemtime(Site::getDataDir() . $path);
					$ret[] = [[$path], $lastmod, 'monthly', 0.8];
				}
			}
		}*/
		return $ret;
		
	}
	
	public function actionIndex($fullpath)
	{
		$fullpath = Site::getDataDir() . $fullpath;
		if (Fs::exists($fullpath))
		{
			header('Content-Type: ' . Fs::getMime($fullpath));
			header('Content-Length: ' . Fs::getSize($fullpath));
			readfile($fullpath);
			exit;
		}
		return null;
	}
	
}