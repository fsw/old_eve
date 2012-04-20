<?php
/**
 * 
 * @author fsw
 *
 */
namespace Cado;
use Widget;

class Action
{
	static function execute()
	{
		new Widget('html');
		static::getBody();
	}
	
	static function getBody()
	{
	
	}
}
