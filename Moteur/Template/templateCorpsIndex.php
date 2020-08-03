<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="Description" content="<?= DESCRIPTION_PAGE_ACCUEIL ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./Images/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="./style/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="./style/modifications.css"/>
    <title><?= NOM_PAGE_ACCUEIL ?> : <?php NOM_DU_SITE ?></title>
</head>
<body>


<div class="contenu container-fluid">
    <h1 id='titre_accueil'><?= NOM_DU_SITE ?></h1>
    <p class='sous-titre lead'><?= DESCRIPTION_PAGE_ACCUEIL ?></p>
    <?php foreach ($listeIndexArticle as $resumeArticle) { ?>
        <article>
            <header>
                <h2>
                    <small> <a
                            href='<?= $resumeArticle['url'] . '.' . 'php' ?>'><?= $resumeArticle['titre'] ?></a>
                    </small>

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
