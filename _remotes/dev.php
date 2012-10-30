<?php

$config = array(
		
		'proto' => 'dev',
		
		'devAgent' => 'FireFlux',
		'devEmail' => 'francio@franico.pl',
		
		'domain' => 'eve',
		
		'apacheCfg' => dirname(dirname(__FILE__)) . '/webroots/apache.cfg',
		'webroots' => dirname(dirname(__FILE__)) . '/webroots/',
		
		'dbConfig' => array(
				'dsn' => 'mysql:host=localhost;dbname=cado',
				'user' => 'cado',
				'pass' => 'cado',
				'prefix' => 'cado_',
		),
		
		'fileCache' => '/tmp/cado/',
		
);
