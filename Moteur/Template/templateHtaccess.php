
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/shtml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
<IfModule mod_headers.c>
    Header always set X-FRAME-OPTIONS "DENY"
    Header always set X-XSS-Protection "1; mode=block"
    Header set Content-Security-Policy "script-src 'self' https://www.google.com"
    Header always set X-Content-Type-Options "nosniff"
</IfModule>
<IfModule mod_rewrite.c>
    <?php if (REDIRECTION_HTTPS) { ?>
        RewriteEngine On
        RewriteCond %{HTTPS} !on
        RewriteRule ^.*$ https://%{SERVER_NAME}%{REQUEST_URI} [R=301,L]
        RewriteRule ^([/0-9a-zA-Z-]+)\.html$ test.php?resource=$1 [L,QSA]
    <?php } ?>
</IfModule>
<?php
if (file_exists(REPERTOIRE_DESTINATION_RENDU_PHP . '/' . "404.php")) { ?>
    ErrorDocument 404 <?=ADRESSE_EXACTE_SITE?>/404.php
<?php }
if (file_exists(REPERTOIRE_DESTINATION_RENDU_PHP . '/' . "403.php")) { ?>
    ErrorDocument 403 <?=ADRESSE_EXACTE_SITE?>/403.php
    <?php
}
?>
Options All -Indexes
