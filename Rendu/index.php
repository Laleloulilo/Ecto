<?php
$timestamp_debut = microtime(true);
require_once('../Moteur/Scheduler.php');
processusGlobalGenerationSite();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="Description" content="Mini-CMS, 0 dépendance, - de 100 ko, Markdown, Déploiement Copier/coller">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./Images/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="./style/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="./style/modifications.css"/>
    <title>Accueil : </title>
</head>
<body>


<div class="contenu container-fluid">
    <h1 id='titre_accueil'>Ecto</h1>
    <p class='sous-titre lead'>Mini-CMS, 0 dépendance, - de 100 ko, Markdown, Déploiement Copier/coller</p>
            <article>
            <header>
                <h2>
                    <small> <a
                            href='Article-Dans-Categorie.php'>Article Dans Catégorie</a>
                    </small>

                </h2>
                <small>22 June 2020 • <em>
                        Catégorie</em>
                    • ☕️ 1 minute</small>
            </header>
            <p>Premier article d'une catégorie</p>
        </article>
                <article>
            <header>
                <h2>
                    <small> <a
                            href='Bienvenue.php'>Bienvenue</a>
                    </small>

                </h2>
                <small>12 June 2020 • <em>
                        Notes</em>
                    • ☕️ 2 minutes</small>
            </header>
            <p>Présentation et mode d'emploi</p>
        </article>
        <?php
// timestamp en millisecondes de la fin du script
$timestamp_fin = microtime(true);
// différence en millisecondes entre le début et la fin
$difference_ms = $timestamp_fin - $timestamp_debut;
// affichage du résultat
?>
<p class="timer"><small>Génération de la page : <?=round($difference_ms * 1000, 1)?> millisecondes.</small></p>
</div>
</body>
</html>

