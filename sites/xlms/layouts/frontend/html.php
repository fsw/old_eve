<?php if (Users\Users::isLoggedIn()) { ?>
	<div class="navigation">
	<?php $user = Users\Users::getLoggedIn() ?>
	<?php echo $user['email']; ?>
	<a href="<?php echo Users\Routing::linkToAction('logout') ?>">(logout)</a>
	
	<form action="<?php echo Routing::linkToAction('index') ?>">
	<?php echo (new field_Enum(array(
			'ALL' => 'ALL',
			'OPEN' => 'OPEN',
			'new' => 'New',
			'assigned' => 'Assigned',
			'inprogress' => 'In progress',
			'blocked' => 'Blocked',
			'RESOLVED' => 'RESOLVED',
			'fixed' => 'Fixed',
			'pushed' => 'Pushed',
			'closed' => 'Closed'
		)))->getFormInput('search[status]', Request::getParam('search')['status']); ?>
	in
	<?php echo (new relation_One('Projects'))->getFormInput('search[project]', Request::getParam('search')['project']); ?>
	<br/>
	<?php echo (new field_Text(['placeholder' => 'Search']))->getFormInput('search[query]', Request::getParam('search')['query']); ?>
	<br/>
	<input type="submit" class="button" value="GO"/>
	</form>
	<br/>
	<a href="<?php echo Routing::linkToAction('projects') ?>">manage projects</a>
	<br/>
	<a href="<?php echo Pages\Routing::linkToPage('help') ?>">Help</a>
	<br/>
	</div>
<?php } ?>
<?php if (Routing::$actionName == 'index') { ?>
<ul id="tabnav">
	<li <?php echo Routing::$params['view'] == 'list' ? 'class="current"' : ''; ?>><a href="<?php echo Routing::linkToAction('index', 'list', Routing::$params['search']) ?>">List</a></li>
	<li <?php echo Routing::$params['view'] == 'calendar' ? 'class="current"' : ''; ?>><a href="<?php echo Routing::linkToAction('index', 'calendar', Routing::$params['search']) ?>">Calendar</a></li>
</ul>
<?php } ?>
<div class="body">
<?php echo $this; ?>
</div>