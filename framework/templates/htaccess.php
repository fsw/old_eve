
<IfModule mod_headers.c>
	Header set X-UA-Compatible "IE=edge"
</IfModule>

# php_flag magic_quotes_gpc Off

ErrorDocument 503 /maintenance.php

<IfModule mod_rewrite.c>

	RewriteEngine On
	
	RewriteCond %{HTTP_USER_AGENT} ^<?php echo $this->devAgent; ?>
	
	RewriteRule ^(.*)$ index.dev.php [QSA,L]
	
	RewriteCond maintenance.lock -f
	RewriteRule ^(.*)$ maintenance.php [QSA,L]
	
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^(.*)$ index.php [QSA,L]
	
</IfModule>
