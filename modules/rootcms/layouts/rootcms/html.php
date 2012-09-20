<?php if ($this->logged): ?>
<ul id="mainMenu">
	<li><a href="#">Modeler</a>
		<ul>
		<?php foreach (Cado::getDescendants('Model') as $model): ?>
		<li><a href="<?php echo actions_Rootcms::hrefModeler($model); ?>"><?php echo $model; ?></a></li>
		<?php endforeach; ?>
		</ul>
	</li>
	
	<li><a href="#">Modules</a>
		<ul>
		<?php foreach (Fs::listDirs('modules') as $module): ?>
		<li><a href="<?php echo actions_Rootcms::hrefModule($module); ?>"><?php echo $module; ?></a></li>
		<?php endforeach; ?>
		<li><a href="<?php echo actions_Rootcms::hrefModeler($model); ?>">...</a></li>
		</ul>
	</li>
	
	<li><a href="<?php echo actions_Rootcms::hrefModeler($model); ?>">Config</a></li>
	
	<li><a href="#">Db</a>
		<ul>
		<li><a href="<?php echo actions_Rootcms::hrefDbCheck(); ?>">check</a></li>
		<li><a href="<?php echo actions_Rootcms::hrefModeler($model); ?>">adminer</a></li>
		</ul>
	</li>
	
	<li><a href="#">Tools</a>
		<ul>
		<li><a href="<?php echo actions_Rootcms::hrefDbCheck(); ?>">sanity check</a></li>
		</ul>
	</li>
	
	<li class="logout"><a href="<?php echo actions_Rootcms::hrefLogout(); ?>">Logout</a></li>
</ul>
<?php endif; ?>
<div id="body">
<?php echo $this->widget; ?>
</div>