
<IfModule mod_headers.c>
	Header set X-UA-Compatible "IE=edge"
</IfModule>

<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
