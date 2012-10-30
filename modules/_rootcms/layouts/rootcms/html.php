<?php if ($this->logged): ?>
	<?php echo Html::ulTree($this->mainMenu, function($row){
					return '<a href="' . $row['href'] . '">' . $row['title'] . '</a>';
				}, 'children', array('id' => 'mainMenu')); ?>
<?php endif; ?>
<div id="body">
<?php echo $this->widget; ?>
</div>
<div class="footer">
	Powered by <a href="http://cadosolutions.com/framework">CadoFramework</a>
</div>