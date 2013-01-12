<?php
/**
 * Captcha generation and verification.
 * 
 * 
 * @package Core
 * @author fsw
 */

class field_Captcha extends Field
{
	public function getFormInput($key, $value)
	{
		return Captcha::formInput($key);
	}
	
	public function validate($value)
	{
		return true;
	}
	
}
