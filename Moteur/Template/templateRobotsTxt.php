User-agent: *
Sitemap : <?=ADRESSE_EXACTE_SITE?>/<?=NOM_FICHIER_SITEMAP?>

Allow:/
<?php foreach ($listeRepertoiresInterdits as $dossier) { ?>
Disallow: /<?= $dossier ?>/
<?php
}
?>
