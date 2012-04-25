<?php
/**
 *
 * @author fsw
 *
 */
chdir('..');
require_once('core/Autoloader.php');
Autoloader::init();
ErrorHandler::init();
//TODO
//Autoloader::setProject(ucfirst(current(explode('.', $_SERVER['HTTP_HOST']))));
//$controllerClass = Autoloader::getProject() . '\\Controller';
 
Project\Controller::controll();
