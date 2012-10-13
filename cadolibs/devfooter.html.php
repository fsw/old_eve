<div id="devfooter">
	<h2>Profiler</h2>
	<h3>Events</h3>
	<?php foreach (self::$events as $class => $events): ?>
		<h4><?php echo $class ?></h4>
		<ul>
			<?php foreach ($events as $event): ?>
				<li><?php echo $event ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>
	<h3>Timers</h3>
	<ul>
	<?php foreach (self::$times as $name => $time): ?>
		<li><?php echo $name ?> <?php echo $time ?></li>
	<?php endforeach; ?>
	</ul>
</div>