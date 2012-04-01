<?php

require_once('../lib/Autoloader.php');
ErrorHandler::init();

if(file_exists('../project/'))
{
  require_once('../project/bootstrap.php');
}
else
{
  $projectName = current(explode('.', $_SERVER['HTTP_HOST']));
  require_once('../projects/' . $projectName . '/bootstrap.php');
}

