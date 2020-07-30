<?php
// timestamp en millisecondes de la fin du script
$timestamp_fin = microtime(true);
// différence en millisecondes entre le début et la fin
$difference_ms = $timestamp_fin - $timestamp_debut;
// affichage du résultat
?>
<p class="timer"><small>Génération de la page : <?=round($difference_ms * 1000, 1)?> millisecondes.</small></p>;
</div>
</body>
</html>

