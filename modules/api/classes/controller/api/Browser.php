<?php 

class controller_api_Browser extends controller_Layout
{
	protected $layoutName = 'apibrowser';
	protected $models;
	
	public function before($method, $args)
	{
		parent::before($method, $args);
		$models = Site::getModels(); 
		$this->layout->models = array_combine($models, array_map(function($m){ return controller_api_Browser::hrefModel($m); }, $models));
		$this->models = $models;
	}
	
	public function actionIndex()
	{
		
	}
	
	public function actionModel($model)
	{
		if (in_array($model, $this->models));
		$this->model = $model;
		$methods = array();
		$className = 'model_' . ucfirst($model);
		foreach (get_class_methods($className) as $method)
		{
			$methods[$method] = array();
			$reflection = new ReflectionMethod($className, $method);
			foreach ($reflection->getParameters() as $param)
			{
				$methods[$method][$param->getName()] = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
			}
		}
		$this->methods = $methods;
	}
}
