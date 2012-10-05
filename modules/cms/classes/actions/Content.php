<?php

class actions_Content extends actions_Frontend
{
	public function actionIndex($slug)
	{
		$model = $this->site->model('content');
		$this->content = $model->getByField('slug', $slug);
		$this->assert(!empty($content));
		//return new Widget('content', $content);
	}
}
