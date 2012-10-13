<?php

class actions_Webmail extends BaseActions
{
	public function actionIndex($template, Array $data)
	{
		$data['webViewLink'] = '#';
		$htmlBody = new Template('mails/' . $template . '/mail.html', $data);
		return $htmlBody;
	}
	
}