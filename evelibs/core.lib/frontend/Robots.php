<?php

class frontend_Robots extends Controller
{
	private function recCheckMap(&$ret, $array, $allow = true, $path = '/') 
	{
		if (!empty($array['_class']))
		{
			if ($array['_class']::$allowRobots != $allow)
			{
				$allow = $array['_class']::$allowRobots;
				$ret[] = ($allow ? 'Allow: ' : 'Disallow: ') . $path;
			}
		}
		foreach ($array as $key => $sub)
		{
			if (is_array($sub))
			{
				$this->recCheckMap($ret, $sub, $allow, $path . $key . '/');
			}
		}
	}
	
	public function actionIndex($extension)
	{
		$this->assert($extension == 'txt');
		$this->setHeader('Content-type', 'text/plain');
		$ret[] = 'User-agent: *';
		$this->recCheckMap($ret, Site::getWebActionsMap());
		$ret[] = 'Sitemap: /sitemap.xml';
		return implode(NL, $ret);
	}
}