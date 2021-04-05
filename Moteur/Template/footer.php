<?php
$timestamp_fin = microtime(true);
$difference_ms = $timestamp_fin - $timestamp_debut;
?>
<p class="timer"><small>Génération de la page : <?=round($difference_ms * 1000, 1)?> millisecondes.</small></p>
</div>
</body>
</html>

