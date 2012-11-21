<?php

class controller_Content extends controller_Frontend
{
	public function actionIndex($slug)
	{
		$widget = new Widget('widgets/contentIndex');
		$model = Site::model('contents');
		$content = $model->getByField('slug', $slug);
		$widget->title = $content['title'];
		$widget->subtitle = $content['subtitle'];
		$widget->body = $content['body'];
		//var_dump();
		//$this->assert(!empty($content));
		return $widget;
	}
}
