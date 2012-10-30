<div id="wrapper">
	<div id="header">
		<ul id="menu">
			<li><a <?php if ($this->method == 'index') echo 'class="current"'; ?> title="About" href="/">About</a></li>
			<li><a <?php if ($this->method == 'intro') echo 'class="current"'; ?> title="Quick Start" href="/intro.html">Quick Start</a></li>
			<li><a <?php if ($this->method == 'docs') echo 'class="current"'; ?> title="Documentation" href="/docs/">Documentation</a></li>
		</ul>
	</div>
	<div id="body">
	<?php echo $this->unsecured('widget') ?> 
	</div>
	<div class="footer">
		Powered by <a href="http://eve.cadosolutions.com/">EveFramework</a>
	</div>
</div>