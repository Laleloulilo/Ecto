<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="Description" content="<?= DESCRIPTION_PAGE_ACCUEIL ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="<?= NOM_PAGE_ACCUEIL ?> : <?= NOM_DU_SITE ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?=ADRESSE_EXACTE_SITE ?>" />
    <meta property="og:image" content="<?=ADRESSE_EXACTE_SITE.'/'.REPERTOIRE_IMAGE.'/'.NOM_IMAGE_OPEN_GRAPH ?>" />
    <link rel="icon" type="image/png" href="./Images/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="./style/main.css" onload="this.media='all'"/>
    <title><?= NOM_PAGE_ACCUEIL ?> : <?= NOM_DU_SITE ?></title>
</head>
<body>


<div class="contenu container-fluid">
    <h1 id='titre_accueil'><?= NOM_DU_SITE ?></h1>
    <p class='sous-titre lead'><?= DESCRIPTION_PAGE_ACCUEIL ?></p>
    <?php foreach ($listeIndexArticle as $resumeArticle) { ?>
        <article>
            <header>
                <h2 class="titre-homepage">
                    <a href='<?= $resumeArticle['url'] . '.' . 'php' ?>'><?= $resumeArticle['titre'] ?></a>
                </h2>
                <small><?= $resumeArticle['date'] ?> • <em>
                        <?= $resumeArticle['categorie'] ?></em>
                    • <?= $resumeArticle['nbMots'] ?></small>
            </header>
            <p><?= $resumeArticle['description'] ?></p>
        </article>
        <?php
    }
    ?>
