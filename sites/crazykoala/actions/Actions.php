<?php

class Actions extends BaseActions
{
	
	public function _pre($request)
	{
		
	}
	
	public function index()
	{
		return new Widget('index');
	}
	
	public function about()
	{
		die('YUPI!!!');
	}
	
	public function _post($response)
	{
		return new Layout('frontend');
	}
	
}