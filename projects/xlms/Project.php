<?php

class Project implements iProject
{
	public static function getModules()
	{
		return array('cado', 'users');
	}

	static function run(Request $request)
	{
		$html = new html_Widget($request);
		$html->addChild('body', new layout_Widget($request));
		echo $html;
	}
}