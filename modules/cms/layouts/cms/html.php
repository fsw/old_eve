<?php if ($this->logged): ?>
<ul id="mainMenu">
	
	<li><a href="#">Data...</a>
		<ul>
			<?php foreach ($this->dataMenu as $name => $href): ?>
			<li><a href="<?php echo $href; ?>"><?php echo $name; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</li>
	
	<li><a href="<?php echo actions_cms_Users::hrefIndex(); ?>">Users</a>
		<ul>
		<li><a href="<?php echo actions_cms_Groups::hrefIndex(); ?>">Groups</a></li>
		<li><a href="<?php echo actions_cms_Privilages::hrefIndex(); ?>">Privilages</a></li>
		</ul>
	</li>
	
	
	<li><a href="#">Config...</a>
		<ul>
		<?php foreach ($this->modules as $code => $name): ?>
		<li><a href="<?php echo actions_Cms::hrefConfig($code); ?>"><?php echo $name; ?></a></li>
		<?php endforeach; ?>
		</ul>
	</li>
	
	<li><a href="#">Tools...</a>
		<ul>
		<li><a href="<?php echo actions_Cms::hrefExport(); ?>">Backup (export)</a></li>
		<li><a href="<?php echo actions_Cms::hrefImport(); ?>">Restore (import)</a></li>
		<li><a href="<?php echo actions_Cms::hrefCache(); ?>">Cache</a></li>
		<li><a href="<?php echo actions_Cms::hrefErrors(); ?>">Errors</a></li>
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