<?php

class tasks_Admin extends controller_Tasks
{
	public function actionMaintenanceOn()
	{
		echo 'turning maintenance ON';
		Fs::write(Site::getWebroot() . 'maintenance.lock', 'Y');
	}
	
	public function actionMaintenanceOff()
	{
		echo 'turning maintenance OFF';
		Fs::write(Site::getWebroot() . 'maintenance.lock', 'Y');
	}
}