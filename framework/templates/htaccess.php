
<IfModule mod_headers.c>
	Header set X-UA-Compatible "IE=edge"
</IfModule>

<IfModule mod_rewrite.c>

	RewriteEngine On
	
	RewriteCond %{HTTP_USER_AGENT} ^<?php echo $this->devAgent; ?>
	
	RewriteRule ^(.*)$ index.dev.php [QSA,L]
	
	RewriteCond maintenance.lock -f
	RewriteRule ^(.*)$ maintenance.html [QSA,L,R=503]
	
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^(.*)$ index.php [QSA,L]
	
</IfModule>
