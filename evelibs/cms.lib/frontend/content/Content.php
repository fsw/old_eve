<?php

class frontend_Content extends Frontend
{
	static public function sitemapIndex()
	{
		$ret = [];
		foreach (model_Contents::getAll() as $contents)
		{
			$ret[] = [[$contents['slug']], $contents['modified'], 'monthly', 0.8];
		}
		return $ret;
	}
	
	public function actionIndex($slug)
	{
		$content = model_Contents::getByField('slug', $slug);
		$this->title = $content['title'];
		$this->subtitle = $content['subtitle'];
		$this->body = $content['body'];
	}
}
