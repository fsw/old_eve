<img src="favicon.png">
<div>
	<h1><?php echo $this->message ?> (<?php echo $this->count ?>)</h1>
		<a href="<?php echo $this->url ?>"><?php echo $this->url ?></a>
		<br/>
		<?php echo $this->file ?>:<?php echo $this->line ?> [code: <?php echo $this->code ?>]
	
	<h2>trace</h2>
		<?php print_r($this->trace); ?>
	
	<h2>server</h2>
		<?php print_r($this->server); ?>
</div>

<a href="<?php echo $this->webViewLink ?>">web view</a>