<?php

class actions_Uploads extends BaseActions
{
	public function actionIndex($fullpath)
	{
		$fullpath = Cado::$outputCache . 'uploads/' . $fullpath;
		if (CADO_DEV && Fs::exists($fullpath))
		{
			header('Content-Type: ' . Fs::getMime($fullpath));
			header('Content-Length: ' . Fs::getSize($fullpath));
			return Fs::read($fullpath);
		}
	}
	
}