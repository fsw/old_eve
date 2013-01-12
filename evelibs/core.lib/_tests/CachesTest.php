<?php
class CachesTest extends PHPUnit_Framework_TestCase
{
	private function getRandomKey()
	{
		for ($i = rand(0,9); $i < 10; $i ++)
		{
			$ret[] = uniqid();
		}
		return $ret;
	}
	
	private function doTestCache($class, $key, $depth = 100)
	{
		if (!Eve::useCache($key))
		{
			$this->markTestSkipped('Cache disabled');
			return;
		}
		$array = array('final' => 'test', 'dummy' => 'array');
		$startKey = $this->getRandomKey();
		//set
		$key = $startKey;
		for ($i=0; $i<$depth; $i++)
		{
			$class::set(implode(DS, $key), $key = $this->getRandomKey());
		}
		$class::set(implode(DS, $key), $array);
		 
		//get
		$key = $startKey;
		for ($i=0; $i<$depth+1; $i++)
		{
			$key = $class::get(implode(DS, $key));
			if ($key === null)
			{
				break;
			}
		}
		$this->assertTrue($key === $array);
		 
		//delete
		$new = $startKey;
		for ($i=0; $i<$depth+1; $i++)
		{
			$new = $class::get(implode(DS, $key = $new));
			if ($new === null)
			{
				break;
			}
			$class::del(implode(DS, $key));
		}
		$this->assertTrue(($i == $depth + 1) && ($class::get(implode(DS, $key)) === null));
	}
	
    public function testArrayCache()
    {
    	$this->doTestCache('cache_Array', 'array');
    }
    
    public function testApcCache()
    {
    	$this->doTestCache('cache_Apc', 'apc');
    }
    
    public function testMemcachedCache()
    {
    	$this->doTestCache('cache_Memcached', 'memcached');
    }
    
}
