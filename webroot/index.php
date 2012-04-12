<?php

foreach (array('Autoloader', 'ErrorHandler') as $coreClass)
{
  require_once('../core/' . $coreClass . '.php');
  {'Core\\'.$coreClass}::init();
}

Core\Autoloader::setProjectRoot('projects/' . current(explode('.', $_SERVER['HTTP_HOST'])) . '/');
Controller::controll();

