<?php

if (!User::getId())
{
  Response::redirect('user/login');
}

Controller::process(new Request());

