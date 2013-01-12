<?php if ($this->logged): ?>
<ul id="mainMenu">
	
	<li><a href="#">Data...</a>
		<ul>
			<?php foreach ($this->dataMenu as $name => $href): ?>
			<li><a href="<?php echo $href; ?>"><?php echo $name; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</li>
	
	<li><a href="#">Config...</a>
		<ul>
		<?php foreach ($this->modules as $code => $name): ?>
		<li><a href="<?php echo Site::lt('cms/config', $code); ?>"><?php echo $name; ?></a></li>
		<?php endforeach; ?>
		</ul>
	</li>
	
	<li><a href="#">Tools...</a>
		<ul>
		<li><a href="<?php echo Site::lt('cms/export'); ?>">Backup (export)</a></li>
		<li><a href="<?php echo Site::lt('cms/import'); ?>">Restore (import)</a></li>
		<li><a href="<?php echo Site::lt('cms/cache'); ?>">Cache</a></li>
		<li><a href="<?php echo Site::lt('cms/errors'); ?>">Errors</a></li>
		<li><a href="<?php echo Site::lt('cms/missing'); ?>">Missing</a></li>
		</ul>
	</li>
	<li class="logout"><a href="<?php echo Site::lt('cms/users/logout'); ?>" title="Logout">&nbsp;</a></li>
	<li class="user">
		logged in as
		<br/>
		<b><?php echo $this->user['email']; ?></b>
	</li>
	
	<li class="users"><a href="#">CMS access...</a>
		<ul>
		<li><a href="<?php echo Site::lt('cms/data/users'); ?>">Users</a></li>
		<li><a href="<?php echo Site::lt('cms/data/groups'); ?>">Groups</a></li>
		</ul>
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