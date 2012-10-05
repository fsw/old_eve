<VirtualHost *:80>
  <?php $first = true; ?>
  <?php foreach ($this->domains as $domain): ?>
  <?php echo $first ? 'ServerName' : 'ServerAlias'; $first = false; ?> <?php echo $domain; ?>
  
  <?php endforeach; ?>
  DocumentRoot <?php echo $this->root; ?>/webroots/<?php echo $this->code ?>
  
  <Directory />
	AllowOverride All
	Options FollowSymLinks
	Order allow,deny
	allow from all
  </Directory>
</VirtualHost>
		  
