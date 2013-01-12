<?php

class frontend_Captcha extends Controller
{
	public static $allowRobots = false;
	
	public function actionGet($extension)
	{
		$this->assert($extension == 'png');
		Captcha::render();
		exit();
	}
}