<?php 
/** 
 * @package CadoLibs
 * @author fsw
 */

if (!CADO_DEV)
{
	class Dev
	{
		public static function startTimer($name){}
		
		public static function stopTimer(){}
		
		public static function logEvent($class){}
		
		public static function showDevFooter(){}
	}
}
else
{
	class Dev {
	
		private static $events = array();
		
		private static $timerNamesStack = array();
		private static $timerStartsStack = array();
		private static $times = array();
		
		public static function startTimer($name)
		{
			array_push(self::$timerStartsStack, microtime(true));
			array_push(self::$timerNamesStack, $name);
		}
		
		public static function stopTimer()
		{
			$name = array_pop(self::$timerNamesStack);
			if (empty(self::$times[$name]))
			{
				self::$times[$name] = 0;
			}
			$time = microtime(true) - array_pop(self::$timerStartsStack);
			self::$times[$name] += $time;
		}
		
		public static function logEvent($class)
		{
			$args = func_get_args();
			array_shift($args);
			self::$events[$class][] = $args;
		}
		
		public static function showDevFooter()
		{
			self::startTimer('dev');
			$errors = Site::model('errors')->getAll();
			self::stopTimer();
			while (!empty(self::$timerNamesStack))
			{
				self::stopTimer();
			}
			require(Cado::findResource('widgets/devfooter.html.php'));
		}
	}
}