<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="Description" content=" <?= $description ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="<?= $titre ?> : <?= NOM_DU_SITE ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?=ADRESSE_EXACTE_SITE.'/'.$url.'.php' ?>" />
    <meta property="og:image" content="<?=ADRESSE_EXACTE_SITE.'/'.REPERTOIRE_IMAGE.'/'.NOM_IMAGE_OPEN_GRAPH ?>" />
    <link rel="icon" type="image/png" href="./Images/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="./style/main.css" onload="this.media='all'"/>
    <title><?= $titre ?> : <?= NOM_DU_SITE ?></title>
</head>
<body>

<div class="contenu container-fluid">
    <div id='titre_accueil'>
        <a href='<?= ADRESSE_EXACTE_SITE ?>'><?= NOM_DU_SITE ?></a>
    </div>
    <p class='sous-titre lead'><?= DESCRIPTION_PAGE_ACCUEIL ?></p>
    <article class="article-seul">
        <header>
            <h1 class="titre-article"><?= $titre ?></h1>
            <?php if ($formatArticle) { ?>
                <small><?= $date ?>
                    <?php if ($categorie != null) { ?>
                        • <em><?= $categorie ?></em>
                    <?php } ?>
                    • <?= $nbMots ?> </small>
            <?php } ?>
        </header>
        <p><?= $contenu ?></p>
    </article>


