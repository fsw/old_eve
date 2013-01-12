<?php 

class api_Browser extends controller_Layout
{
	public static $allowRobots = false;
	
	public function actionIndex()
	{
		$this->models = Eve::getDescendants('Model');
		foreach ($this->models as &$model)
		{
			$model = ['class' => $model];
			$model['code'] = lcfirst(str_replace('model_', '', $model['class']));
			$model['methods'] = [];
			foreach (get_class_methods($model['class']) as $method)
			{
				$model['methods'][$method] = array();
				$reflection = new ReflectionMethod($model['class'], $method);
				foreach ($reflection->getParameters() as $param)
				{
					$model['methods'][$method][$param->getName()] = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
				}
			}
		}
	}
}
