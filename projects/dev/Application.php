<?php

class Application implements iApplication
{
	static function run(Request $request)
	{
		$html = new html_Widget($request);
		$html->addChild('body', new list_Widget($request));
		echo $html;
	}
}
