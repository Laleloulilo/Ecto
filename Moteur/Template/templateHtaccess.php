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
    Header set Content-Security-Policy "default-src 'self' https://www.google-analytics.com 'unsafe-inline'"
    Header always set X-Content-Type-Options "nosniff"
</IfModule>
<IfModule mod_rewrite.c>
    <?php if (REDIRECTION_HTTPS) { ?>
        RewriteEngine On
        RewriteCond %{HTTPS} off [OR]
        RewriteCond %{HTTP_HOST} !^www\. [NC]
        RewriteCond %{HTTP_HOST} ^(?:www\.)?(.+)$ [NC]
        RewriteRule ^.*$ https://www.%1%{REQUEST_URI} [L,NE,R=301]
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
