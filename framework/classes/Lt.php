<?php 
/**
 * 
 * @author fsw
 * 
 */
class Lt 
{
	static function __callStatic($method, $arguments)
	{
		if (strpos($method, 'href') === 0)
		{
			$method = 'action' . substr($method, 4);
			return Site::unroute(get_called_class(), $method, $arguments);
		}
	}
}