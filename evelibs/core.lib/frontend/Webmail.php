<?php

class frontend_Webmail extends Controller
{
	public function actionIndex($template, $getData)
	{
		$getData['webViewLink'] = '#';
		return new Template('emails/' . $template . '/body.html', $getData);;
	}
	
}