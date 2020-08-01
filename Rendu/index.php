<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Bienvenue sur Ecto</title>
</head>
<body>
<div>
    <?php
    require_once('../Moteur/Scheduler.php');
    $timestamp_debut = microtime(true);
    processusGlobalGenerationSite();
    // timestamp en millisecondes de la fin du script
    $timestamp_fin = microtime(true);
    // différence en millisecondes entre le début et la fin
    $difference_ms = $timestamp_fin - $timestamp_debut;
    // affichage du résultat
    ?>
    <p class="timer">Génération de la page : <?= round($difference_ms * 1000, 1) ?> millisecondes.</p>
</div>
</body>
</html>
