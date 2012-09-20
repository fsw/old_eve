<?php

class actions_Search extends BaseActions
{
	
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