<?php

class Api
{
	static function run(Request $request)
	{

		Autoloader::getClassTree('Entity');
		echo 'dupa';
	}
}
