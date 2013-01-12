<?php
class ModelTest extends PHPUnit_Framework_TestCase
{
	
    public function testPrivilages()
    {
    	$setPrivilages = new ReflectionMethod('Model', 'setPrivilages');
    	$clearPrivilages = new ReflectionMethod('Model', 'clearPrivilages');
    	$assertPrivilages = new ReflectionMethod('Model', 'assertPrivilages');
    	
    	$this->assertTrue($setPrivilages->isProtected());
    	$this->assertTrue($clearPrivilages->isProtected());
    	$this->assertTrue($assertPrivilages->isProtected());
    	 
    	$setPrivilages->setAccessible(true);
    	$clearPrivilages->setAccessible(true);
    	$assertPrivilages->setAccessible(true);
    	
    	try {
    		$assertPrivilages->invoke(null, ['privs' => 'root']);
    		$this->assertTrue(false, 'supposed to get exception');
    	} catch (model_Exception $e){ }
    	
    	$setPrivilages->invoke(null, ['uid' => 15, 'gid' => 15, 'privs' => ['root']]);
    	$assertPrivilages->invoke(null, ['privs' => 'root']);
    	$clearPrivilages->invoke(null);
    	try {
    		$assertPrivilages->invoke(null, ['gid' => 15]);
    		$this->assertTrue(false, 'supposed to get exception');
    	} catch (model_Exception $e){ }
    	
    }
    
    
}
