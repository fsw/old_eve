<?php 

if (!CADO_DEV)
{
	class Dev
	{
		public static function startTimer($name){}
		
		public static function stopTimer(){}
		
		public static function logEvent($class, $what){}
		
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
		
		public static function logEvent($class, $what)
		{
			self::$events[$class][] = $what;
		}
		
		public static function showDevFooter()
		{
			require(Cado::findResource('devfooter.html.php'));
		}
	}
}