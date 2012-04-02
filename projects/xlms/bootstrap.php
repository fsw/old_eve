<?php

/*if (!User\User::getId())
{
  Cado\Response::redirect('user/login');
}*/

echo Cado\Controller::process(new Cado\Request());

