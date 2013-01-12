<?php

class frontend_Dev extends Controller
{
	public static $allowRobots = false;
	
	public function before($method, $args)
	{
		$this->assert(Eve::isDev());
		$this->isRoot = (Request::isLocalHost());
	}
	
	public function actionIndex()
	{
	}
	
	public function actionFooter()
	{
		return new Template('frontend/devfooter.html', [
			'errors' => model_Errors::getAll(),
			'stats' => Eve::getStats(),
		]); 
	}
	
	public function actionPoker()
	{
		//RUN ALL CRONS
	}

	public function actionWarmup()
	{
		return 'OKOKOK' . NL;
	}
	
	public function actionClearcache($what = null)
	{
		if ($this->isRoot)
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
			echo 'CLEARED';
		}
		else
		{
			echo 'FORBIDDEN';
		}
		return NL;
	}
}