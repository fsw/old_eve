<?php if ($this->logged): ?>
<ul id="mainMenu">
	
	<li><a href="#">Data...</a>
		<ul>
			<?php foreach ($this->dataMenu as $model => $name): ?>
			<li><a href="<?php echo actions_Cms::hrefList($model); ?>"><?php echo $name; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</li>
	
	<li><a href="<?php echo actions_Cms::hrefList('users'); ?>">Users</a>
		<ul>
		<li><a href="<?php echo actions_Cms::hrefList('groups'); ?>">Groups</a></li>
		<li><a href="<?php echo actions_Cms::hrefList('privilages'); ?>">Privilages</a></li>
		</ul>
	</li>
	
	
	<li><a href="#">Config...</a>
		<ul>
		<?php foreach ($this->modules as $module): ?>
		<li><a href="<?php echo actions_Cms::hrefModule($module); ?>"><?php echo $module; ?></a></li>
		<?php endforeach; ?>
		<li><a href="<?php echo actions_Cms::hrefModules(); ?>">site</a></li>
		</ul>
	</li>
	
	<li><a href="#">Tools...</a>
		<ul>
		<li><a href="<?php echo actions_Cms::hrefExport(); ?>">Export</a></li>
		<li><a href="<?php echo actions_Cms::hrefCache(); ?>">Import</a></li>
		<li><a href="<?php echo actions_Cms::hrefCache(); ?>">Cache</a></li>
		<li><a href="<?php echo actions_Cms::hrefCheck(); ?>">Sanity check</a></li>
		</ul>
	</li>
	<li class="logout"><a href="<?php echo actions_Users::hrefLogout(); ?>" title="Logout">&nbsp;</a></li>
	<li class="user">
		logged in as <b><?php echo $this->user['name']; ?></b>
		<br/>
		(<b><?php echo $this->user['email']; ?></b>)
	</li>
</ul>
<?php endif; ?>
<div id="body">
<?php //var_dump($this->user) ?>
<?php echo $this->widget; ?>
</div>
<div class="footer">
	Powered by <a href="http://cadosolutions.com/eve">EveFramework</a>
</div>