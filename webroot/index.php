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
Controller::controll(new Request())->output();

