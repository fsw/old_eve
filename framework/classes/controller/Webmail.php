<?php

class controller_Webmail extends Controller
{
	public function actionIndex($template, Array $data)
	{
		$data['webViewLink'] = '#';
		$htmlBody = new Template('mails/' . $template . '/mail.html', $data);
		return $htmlBody;
	}
	
}