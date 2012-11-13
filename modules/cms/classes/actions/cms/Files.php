<?php 

class actions_cms_Files extends actions_cms_Data
{
	protected $model = 'files';
	protected $columns = array('id', 'name', 'file');
	
	public function before($method, $args)
	{
		if ($method=='browser')
		{
			$this->layout = new Layout('layouts/tinyMceWindow');
		}
		else
		{
			parent::before($method, $args);
		}
	}
	
	public function actionBrowser($getType, $getPage)
	{
		$this->layout->addJs('/static/tinymce/jscripts/tiny_mce/tiny_mce_popup.js');
		$widget = new Widget('widgets/cms/fileBrowser');
		$widget->files = Site::model('files')->search();
		return $widget;
	}
	
}