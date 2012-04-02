<?php

require_once('../lib/Autoloader.php');
Cado\ErrorHandler::init();

if(file_exists('../project/'))
{
  $projectRoot = '../project/';
}
else
{
  $projectRoot = '../projects/' . current(explode('.', $_SERVER['HTTP_HOST'])) . '/';
}

require_once($projectRoot . 'config.php');
require_once($projectRoot . 'bootstrap.php');

