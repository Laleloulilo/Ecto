<html>
<body>
<div>
<?php
$timestamp_debut = microtime(true);
require_once('../Moteur/Scheduler.php');
processusGlobalGenerationSite();
// timestamp en millisecondes de la fin du script
$timestamp_fin = microtime(true);
// différence en millisecondes entre le début et la fin
$difference_ms = $timestamp_fin - $timestamp_debut;
// affichage du résultat
echo '<p class="timer">Génération de la page : ' . round($difference_ms * 1000, 1) . ' millisecondes.'."</p>";
?>
</div>
</body>
</html>