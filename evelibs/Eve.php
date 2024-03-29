<?php
/**
 *
 * @author fsw
 *
 */

define('DS', DIRECTORY_SEPARATOR);
define('NL', PHP_EOL);

function __($str)
{
	return $str;
}

final class Eve
{
	private static $devMode = false;
	private static $useCache = [];
	private static $cacheDir = '';
	private static $libRoots = array();
	private static $code = 'cado';
	private static $vendorRoot;
	private static $exception = null;
	//statistics:
	private static $timers = array();
	private static $startTime = 0;
	private static $timeStats = array();
	private static $memStats = array();
	private static $events = array();
	private static $saveStats = true;
	private static $allIncluded = false;
	
	public static function init($libs = [], $cachePath = null, $devMode = false)
	{
		self::$devMode = $devMode;
		self::startTimer('other');
		//TODO move to Eve?
		if (PHP_SAPI !== 'cli')
		{
			session_start();
		}
		self::setCacheDir($cachePath);
		$root = dirname(dirname(__FILE__)) . DS;
		
		foreach (array_reverse($libs) as $lib)
		{
			self::$libRoots[] = $root . $lib . DS;
		}
		self::$libRoots[] = $root . 'evelibs/core.lib' . DS;
		
		spl_autoload_register(['Eve', 'autoload']);
		
		
		self::$vendorRoot = $root . 'evelibs' . DS . 'vendor' . DS;
		
		register_shutdown_function(['Eve', 'shutdown']);
		//self::$errorHandler = new ErrorHandler();
		//TODO
		/*if (get_magic_quotes_gpc())
		{
			function stripslashes_gpc(&$value)
			{
				$value = stripslashes($value);
			}
			array_walk_recursive($_GET, 'stripslashes_gpc');
			array_walk_recursive($_POST, 'stripslashes_gpc');
			array_walk_recursive($_COOKIE, 'stripslashes_gpc');
			array_walk_recursive($_REQUEST, 'stripslashes_gpc');
		}*/
		
	}
	
	public static function getStats()
	{
		if (self::$devMode)
		{
			$ret = $_SESSION['stats'];
			$_SESSION['stats'] = [];
			self::$saveStats = false;
			return $ret;
		}
	}
			
	public static function shutdown()
	{
		self::stopTimer();
		if (self::$devMode && self::$saveStats)
		{
			if (empty($_SESSION['stats']))
			{
				$_SESSION['stats'] = [];
			}
			$_SESSION['stats'][empty($_SERVER['REQUEST_URI']) ? 0 : $_SERVER['REQUEST_URI']] = [self::$timeStats, self::$memStats, self::$events];
		}
	}
	
	public static function requireVendor($file)
	{
		require_once static::$vendorRoot . $file;
	}
	
	public static function isDev()
	{
		return self::$devMode;
	}
	
	public static function useCache($key = 'core')
	{
		return self::$useCache[$key];
	}
	
	public static function getCacheDir()
	{
		return self::$cacheDir;
	}
	
	public static function setCacheDir($path)
	{
		self::$cacheDir = $path . DS;
		if (!empty($path) && !is_dir($path . DS .'classes'))
		{
			mkdir($path . DS .'classes', 0777, true);
		}
		foreach (['core', 'apc', 'array', 'memcached'] as $key)
		{
			self::$useCache[$key] = !empty($path) && (!self::$devMode || !empty($_COOKIE['use_cache'][$key]));
		}
	}
	
	public static function stackException(Exception $e)
	{
		self::$exception = $e;
	}
	
	public static function stackedException()
	{
		return self::$exception;
	}
	
	public static function autoload($className)
	{
		self::startTimer('autoload');
		self::logEvent('autoload', $className);
		if (self::$useCache['core'] && file_exists($path = self::$cacheDir . 'classes' . DS . $className . '.php'))
		{
			require $path;			
		}
		else
		{
			$file = self::getClassFileName($className);
			if ($file !== null)
			{
				require $file;
				if (self::$useCache['core'])
				{
					//save to cache
		    		copy($file, Eve::getCacheDir() . 'classes' . DS . $className . '.php');
				}
			}
		}
		self::stopTimer();
	}
	
	public static function getClassFileName($className)
	{
		$path = explode('_', $className);
		$baseName = array_pop($path);
		$path = implode(DS, $path);
		foreach (static::$libRoots as $root)
		{
			$searchFiles[] = $root . (empty($path) ? '' : $path . DS) . $baseName . '.php';
			$searchFiles[] = $root . (empty($path) ? '' : $path . DS) . lcfirst($baseName) . DS . $baseName . '.php';
		}
		foreach ($searchFiles as $file)
		{
			if (file_exists($file))
			{
				return $file;
			}
		}
		return null;
	}
	
	public static function classExists($className)
	{
		return self::getClassFileName($className) !== null;
	}
	
	public static function find($path)
	{
		self::startTimer('resourceloader');
		//just for windows sake
		$path = str_replace('/', DS, $path);
		$ret = null;
		foreach (static::$libRoots as $root)
		{
			if (file_exists($root . $path))
			{
				$ret = $root . $path;
				break;
			}
		}
		self::stopTimer();
		return $ret;
	}
	
	public static function findAll($path)
	{
		self::startTimer('resourceloader');
		//just for windows sake
		$path = str_replace('/', DS, $path);
		$ret = array();
		foreach (static::$libRoots as $root)
		{
			if (file_exists($root . $path))
			{
				$ret[] = $root . $path;
			}
		}
		self::stopTimer();
		return $ret;
	}
	
	public static function listDir($path)
	{
		self::startTimer('resourceloader');
		//just for windows sake
		$path = str_replace('/', DS, $path);
		$ret = [];
		foreach (static::$libRoots as $root)
		{
			if (is_dir($root . $path))
			{
				$all = Fs::listAll($root . $path);
				foreach ($all as $file)
				{
					$ret[$file] = true;
				}
			}
		}
		self::stopTimer();
		return array_keys($ret);
	}
	
	/**
	 * this function should never be called in a normal application workflow
	 */
	private static function includeAll()
	{
		if (static::$allIncluded)
		{
			return true;
		}
		static::$allIncluded = true;
		$included = array();
		foreach ($classes = get_declared_traits() + get_declared_classes() as $class)
		{
			$included[$class] = true;
		}		
		foreach (static::$libRoots as $root)
		{
			$files = Fs::listFiles(substr($root, 0, -1), true, true);
			foreach ($files as $file)
			{
				$relative = substr($file, strlen($root)); 
				if (strpos($relative, '_') === 0)
				{
					continue;
				}
				
				$ext = substr(basename($file), strpos(basename($file), '.') + 1);
				if (($ext == 'php') && (strpos($file, '.svn') === false))
				{
					$className = substr($file, strlen($root));
					$className = substr($className, 0, strrpos($className, '.php'));
					$className = explode('/', $className);
					if (count($className) > 1)
					{
						$last = array_pop($className);
						$prev = array_pop($className);
						if (ucfirst($prev) == $last)
						{
							$className[] = $last;
						}
						else
						{
							$className[] = $prev;
							$className[] = $last;
						}
					}
					$className = implode('_', $className);
					if (empty($included[$className]))
					{
						require $file;
						$new = array_diff(get_declared_traits() + get_declared_classes(), $classes);
						foreach ($new as $className)
						{
							$included[$className] = true;
						}
						$classes = array_merge($classes, $new);
					}
				}
			}
		}
	}
	
	public static function getDescendants($class)
	{
		self::startTimer('classtools');
		$ret = cache_Array::get('descendants/' . $class);
		if ($ret === null)
		{
			static::includeAll();
			$ret  = array();
			foreach (get_declared_classes() as $c){
				if (is_subclass_of($c, $class))
				{
					$r = new ReflectionClass($c);
					if (!$r->isAbstract())
					{
						$ret[] = $c;
					}
				}
			}
			cache_Array::set('descendants/' . $class, $ret);
		}
		self::stopTimer();
		return $ret;
	}
	
	public static function startTimer($key)
	{
		if (self::$devMode)
		{
			if (empty(self::$startTime))
			{
				self::$startTime = microtime(true);
			}
			array_push(self::$timers, [$key, microtime(true), memory_get_usage(true)]);
			if (!isset(self::$timeStats[$key]))
			{
				self::$timeStats[$key] = 0;
				self::$memStats[$key] = 0;
			}
		}
	}
	
	public static function stopTimer()
	{
		if (self::$devMode)
		{
			list($key, $time, $memory) = array_pop(self::$timers);
			$time = microtime(true) - $time;
			$memory = memory_get_usage(true) - $memory;
			
			self::$timeStats[$key] += $time;
			self::$memStats[$key] += $memory;
			
			foreach(self::$timers as &$timer)
			{
				$timer[1] += $time;
				$timer[2] += $memory;
			}
		}
	}

	public static function logEvent($class)
	{
		if (self::$devMode)
		{
			$args = func_get_args();
			array_shift($args);
			$start_time = microtime(true) - self::$startTime;
			array_unshift($args, $start_time);
			self::$events[$class][] = $args;
		}
	}
}
