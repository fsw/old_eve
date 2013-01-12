<h1 id="test">
	<?php echo $this->title; ?>
</h1>
<div id="content" class="content">
	<p>
		<img src="/images/logo.jpg" alt="Logo" />
		<?php echo $this->body; ?>
	</p>
	<ul class="list">
		<?php for ($i=1; $i<=3; $i++): ?>
			<li><?php echo $i; ?></li>
		<?php endfor; ?>
	</ul>
</div>