<?php

class frontend_Sitemap extends Controller
{
	private $urls = array();
	
	private function recCheckMap($array, $path = '/')
	{
		if (!empty($array['_class']))
		{
			$method = 'sitemap' . substr($array['_method'], strlen('action'));
			if (method_exists($array['_class'], $method))
			{
				$ret = $array['_class']::$method();
				foreach ($ret as $call)
				{
					$this->urls[] = [
						'loc' => Site::ltArray(substr($path, 1, -1), $call[0]),
						'lastmod' => gmdate('c', empty($call[1]) ? Config::get('eve', 'promotetime') : $call[1]),
						'changefreq' => empty($call[2]) ? 'yearly' : $call[2],
						'priority' => empty($call[3]) ? '0.5' : $call[3],
					];
				}
			}
		}
		foreach ($array as $key => $sub)
		{
			if (is_array($sub))
			{
				$this->recCheckMap($sub, $path . $key . '/');
			}
		}
	}
	
	public function actionIndex($extension)
	{
		$this->assert($extension == 'xml');

		$this->setHeader('Content-type', 'text/xml; charset=utf-8');
		$xml = new SimpleXMLElement(
			'<?xml version="1.0" encoding="UTF-8" ?>' . NL . 
			'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ' .
      		'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' .
      		'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" />'
		);
		
		$this->recCheckMap(Site::getWebActionsMap());
		foreach ($this->urls as $url)
		{
			$elem = $xml->addChild('url');
			$elem->addChild('loc', $url['loc']);
			$elem->addChild('lastmod', $url['lastmod']);
			
			if (!empty($url['changefreq']))
			{
				$elem->addChild('changefreq', $url['changefreq']);
			}
			if (!empty($url['priority']))
			{
				$elem->addChild('priority', $url['priority']);
			}
		}
		return $xml->asXML();
		
	}
}