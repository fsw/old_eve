<?php

class frontend_Layouts extends Controller
{
	public function actionIndex($path, $extension)
	{
		//TODO minimize
		switch ($extension)
		{
			case 'js':
				$this->setHeader('Content-type', 'text/javascript');
				$files = [Eve::find('static/core.js')];
				if ($p = Eve::find($path . '.js'))
				{
					$files[] = $p;
				}
				foreach ($files as &$file)
				{
					$file = Fs::read($file);
				}
				return implode(NL, $files);
			case 'img':
			case 'file':
			case 'css':
				$this->setHeader('Content-type', 'text/css');
				$files = [Eve::find('static/reset.css'), Eve::find('static/core.css')];
				foreach ($files as &$file)
				{
					$file = Fs::read($file);
				}
				
				if ($p = Eve::find($path . '.sass'))
				{
					Eve::requireVendor('phamlp/sass/SassParser.php');
					$sass = new SassParser(['cache' => false]);
					$files[] = $sass->toCss($p);
				}
				elseif ($p = Eve::find($path . '.css'))
				{
					$files[] = Fs::read($p);
				}
				return implode(NL, $files);
		}
	}
}