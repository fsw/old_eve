<?php

class Controller extends BaseController
{
	static function run()
	{
		$html = new Widget('html');
		$html->pageTitle = 'XLMS';
		echo $html;
	}
}
