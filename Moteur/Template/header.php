<?php
header('X-Content-Type-Options: nosniff');
header('X-FRAME-OPTIONS: DENY');
header('Content-Security-Policy: base-uri \'self\';');

$timestamp_debut = microtime(true);
require_once('../Moteur/Scheduler.php');
processusGlobalGenerationSite();
?>
