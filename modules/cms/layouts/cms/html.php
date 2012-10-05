<?php if ($this->logged): ?>
<ul id="mainMenu">
	
	<li><a href="#">Tools</a>
		<ul>
		<li><a href="<?php echo actions_Rootcms::hrefDbCheck(); ?>">check Db</a></li>
		<li><a href="<?php echo actions_Rootcms::hrefDbCheck(); ?>">run tests</a></li>
		</ul>
	</li>
	
	<li><a href="<?php echo actions_Rootcms::hrefModules(); ?>">Modules</a>
		<ul>
		<?php foreach (Fs::listDirs('modules') as $module): ?>
		<li><a href="<?php echo actions_Rootcms::hrefModule($module); ?>"><?php echo $module; ?></a></li>
		<?php endforeach; ?>
		<li><a href="<?php echo actions_Rootcms::hrefModules(); ?>">...</a></li>
		</ul>
	</li>
	
	<li><a href="#">Modeler</a>
		<ul>
		<?php foreach (Cado::getDescendants('Model') as $model): ?>
		<li><a href="<?php echo actions_Rootcms::hrefModeler($model); ?>"><?php echo str_replace('model_', '', $model); ?></a></li>
		<?php endforeach; ?>
		</ul>
	</li>
	
	<li><a href="<?php echo actions_Rootcms::hrefConfig(); ?>">Config</a></li>
	
	<li class="logout"><a href="<?php echo actions_Rootcms::hrefLogout(); ?>" title="Logout">&nbsp;</a></li>
	
</ul>
<?php endif; ?>
<div id="body">
<?php echo $this->widget; ?>
</div>
<div class="footer">
	Powered by <a href="http://cadosolutions.com/framework">CadoFramework</a>
</div>