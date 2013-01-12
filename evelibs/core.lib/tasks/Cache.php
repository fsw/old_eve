<?php 

class tasks_Cache extends controller_Tasks
{
	public function actionClear($what = null)
	{
		if (($what === null) || ($what === 'files'))
		{
			$files = Fs::listFiles(Eve::getCacheDir(), false, true);
			$dirs = Fs::listDirs(Eve::getCacheDir(), false, true);
			foreach ($files as $file)
			{
				echo 'removing ' . $file . ' ...' . NL;
				Fs::remove($file);
			}
			foreach ($dirs as $dir)
			{
				echo 'removing ' . $dir . ' ...' . NL;
				Fs::remove($dir, true);
			}
		}
		if (($what === null) || ($what === 'output'))
		{
			$files = Fs::listFiles(Config::get('site', 'webDir') . '/cache', false, true);
			$dirs = Fs::listDirs(Config::get('site', 'webDir') . '/cache', false, true);
			foreach ($files as $file)
			{
				echo 'removing ' . $file . ' ...' . NL;
				Fs::remove($file);
			}
			foreach ($dirs as $dir)
			{
				echo 'removing ' . $dir . ' ...' . NL;
				Fs::remove($dir, true);
			}
		}
		if (($what === null) || ($what === 'apc'))
		{
			echo 'clearing APC' . NL;
			apc_clear_cache();
		}
		return true;
	}
}