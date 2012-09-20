<h1>Projects</h1>
<a id="addButton" href="<?php echo Routing::linkToAction('editProject') ?>" class="button">ADD</a>

<?php function printTree($projects) { ?>
	<?php if (count($projects)) { ?>
	<ul class="projectsList">
	<?php foreach($projects as $project) { ?>
		<li>
		
		<a class="projectLink" href="<?php echo Routing::linkToAction('editProject', $project['id']) ?>"><?php echo $project['name'] ?></a>
		<?php printTree($project['children']); ?>
		</li>
	<?php } ?>
	</ul>
	<?php } ?>
<?php } ?>

<?php printTree($this->projects); ?>




