<?php
/**
 * 
 * @author fsw
 *
 */
class Preprocessor
{
	public function __construct()
	{
		Autoloader::requireVendor('ccpp-0.1b2.class.php');
		$this->pp = new CCPP();
	}

	public function define($name, $value)
	{
		return $this->pp->define($name, $value);
	}
	
	public function parse($code)
	{
		return $code;
		return $this->pp->parse($code);
	}
}