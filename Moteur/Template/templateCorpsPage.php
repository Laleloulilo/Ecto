<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="Description" content=" <?= $description ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./Images/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="./style/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="./style/modifications.css"/>
    <title><?= $titre ?></title>
</head>
<body>

<div class="contenu container-fluid">
    <h2 id='titre_accueil'>
        <a href='<?= ADRESSE_EXACTE_SITE ?>'><?= NOM_DU_SITE ?></a>
    </h2>
    <p class='sous-titre lead'><?php echo DESCRIPTION_PAGE_ACCUEIL ?></p>
    <article>
        <header>
            <h1><?= $titre ?></h1>
            <?php if ($format_article) { ?>
                <small><?= $date ?>
                    <?php if ($categorie != null) { ?>
                        • <em><?= $categorie ?></em>
                    <?php } ?>
                    • <?= $nbMots ?> </small>
            <?php } ?>
        </header>
        <p><?= $contenu ?></p>
    </article>

