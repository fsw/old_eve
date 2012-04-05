<?php

require_once('../core/Autoloader.php');
require_once('../core/ErrorHandler.php');
require_once('../core/Debug.php');

Core\ErrorHandler::init();

if(file_exists('../project/'))
{
  $projectRoot = '../project/';
}
else
{
  $projectRoot = '../projects/' . current(explode('.', $_SERVER['HTTP_HOST'])) . '/';
}

require_once($projectRoot . 'bootstrap.php');
