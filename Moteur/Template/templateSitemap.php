<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<?= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'?>
<?= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'?>
<url>
    <loc><?= ADRESSE_EXACTE_SITE ?>/index.php</loc>
</url>
<?php foreach ($listePages as $page) { ?>
<url>
    <loc><?= ADRESSE_EXACTE_SITE ?>/<?= $page ?>.php</loc>
</url>
<?php
}
?>
</urlset>
