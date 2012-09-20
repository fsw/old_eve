<?php
/**
 *
 * @author fsw
 *
 */

define('CADO_DEV', true);

define('CADO_DB_DSN', 'mysql:host=localhost;dbname=cado');
define('CADO_DB_USER', 'cado');
define('CADO_DB_PASS', 'cado');

define('CADO_FILE_CACHE', '/tmp/cado/');

$sites = array(
	'cado' => 'dev',
	'xlms.cado' => 'xlms',
	'example1.cado' => 'example1',
	'example2.cado' => 'example2',
	'crazykoala.cado' => 'crazykoala',	
	'huntereye.cado' => 'huntereye',	
);

require_once('../core/Cado.php');
Cado::init();

$request = new Request();

echo BaseSite::factory($sites[$request->domain()])->route($request);
