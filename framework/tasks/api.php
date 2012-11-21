<?php 

//list
//list 'model'
//call 'model' 'function' 'arg1' 'arg2'

$models = Site::getModels();

$cmd = array_shift($args);
$model = array_shift($args);
if (!empty($model) && in_array($model, $models))
{
	$obj = Site::model($model);
}

switch($cmd)
{
	case 'list':
		if (!empty($obj))
		{
			var_dump(get_class_methods($obj));
		}
		else
		{
			var_dump($models);
		}
		break;
	case 'call':
		$function = array_shift($args);
		$ret = call_user_func_array(array($obj, $function), $args);
		var_dump($ret);
		break;
	default:
		exit;
}

