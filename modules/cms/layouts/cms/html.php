<?php namespace Cms; ?>
<ul class='menu'>
<li><a href="<?php echo Routing::linkToAction('index') ?>">About</a></li>
<li><a href="<?php echo Routing::linkToAction('data') ?>">Data</a></li>
<li><a href="<?php echo Routing::linkToAction('checkDatabase') ?>">Check database</a></li>
<li><a href="<?php echo \Users\Routing::linkToAction('logout'); ?>">Logout</a></li>
<li><a href="<?php echo \Routing::linkToAction('index'); ?>">Front-end</a></li>
</ul>
<div id='body'>
<?php echo $this; ?>
</div>