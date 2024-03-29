<?php
require_once('Parsedown.php');
require_once('ParsedownExtra.php');
require_once('Constante.php');
require_once('Entites/EnTete.php');
require_once('Entites/Article.php');
require_once('JsonDataBaseBuilder.php');
require_once('PhpPageBuilder.php');
require_once('GenericTools.php');
setlocale(LC_TIME, ZONE_TEMPORELLE_HEURE);

function processusGlobalGenerationSite()
{
    if (analyseRefraichissementDelaiMiseAJourDonnee()) {
        nettoyageEtSetupDossier(REPERTOIRE_BUILD);
        nettoyageEtSetupDossier(REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR);
        nettoyageEtSetupDossier(REPERTOIRE_DESTINATION_JSON);
        nettoyageEtSetupDossier(REPERTOIRE_DESTINATION_RENDU_PHP);
        traiterRepertoireJsonArticleMarkdown(REPERTOIRE_PAGES_ERREUR, REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR, true, true);
        traiterRepertoireJsonArticleMarkdown(REPERTOIRE_BILLETS, REPERTOIRE_DESTINATION_JSON);
        creerListingEntete(REPERTOIRE_DESTINATION_JSON);
        creerListingEntete(REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR);
        copierDossierEtSousDossier(DOSSIER_ELEMENTS_DESIGN_TEMPLATE, REPERTOIRE_DESTINATION_RENDU_PHP);
        dossierExistantOuLeCreer(REPERTOIRE_CONTENU_IMAGE);
        nettoyageEtSetupDossier(REPERTOIRE_RENDU_IMAGE);
        copierDossierEtSousDossier(REPERTOIRE_CONTENU_IMAGE, REPERTOIRE_RENDU_IMAGE);
        rendufichiersArticle(REPERTOIRE_DESTINATION_JSON, REPERTOIRE_DESTINATION_RENDU_PHP);
        rendufichiersArticle(REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR, REPERTOIRE_DESTINATION_RENDU_PHP);
        creationIndexBlog(REPERTOIRE_DESTINATION_JSON, 'en-tete', REPERTOIRE_DESTINATION_RENDU_PHP);
        creationSitemap(REPERTOIRE_DESTINATION_JSON, REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR, 'en-tete', REPERTOIRE_DESTINATION_RENDU_PHP);
        creationRobotsTxT(REPERTOIRE_DESTINATION_RENDU_PHP);
        creationHtaccess(REPERTOIRE_DESTINATION_RENDU_PHP);
        miseAJourTimestamp();
    }
}

function analyseRefraichissementDelaiMiseAJourDonnee()
{
    $mettreAjourTimestampEnregistre = false;
    $miseAJourAFaire = false;
    $timestamp = 0;
    if (file_exists(NOM_FICHIER_VERIFICATION_TIMESTAMP)) {
        if (file_exists(NOM_FICHIER_VERIFICATION_TIMESTAMP)) {
            $timestamp = file_get_contents(NOM_FICHIER_VERIFICATION_TIMESTAMP);
        }
        if (!is_int($timestamp)) {
            if (abs(time() - $timestamp) > NOMBRE_DE_SECONDES_AVANT_MISE_A_JOUR) {
                // mettre à jour car il est temps
                $mettreAjourTimestampEnregistre = true;
            }
        } else {
            // donnée du fichier de timestand fausse, je recrée le fichier et lance la mise à jour
            $mettreAjourTimestampEnregistre = true;
        }
    } else {
        // fichier de timestanp à créer
        $mettreAjourTimestampEnregistre = true;
    }
    if ($mettreAjourTimestampEnregistre) {
        $miseAJourAFaire = verifierNecessiteMiseAJour($timestamp);
        miseAJourTimestamp();
    }
    return $miseAJourAFaire;
}

function verifierNecessiteMiseAJour($timestampDerniereMaj)
{
    clearstatcache();
    dossierExistantOuLeCreer(REPERTOIRE_BILLETS);
    dossierExistantOuLeCreer(REPERTOIRE_TEMPLATE);
    dossierExistantOuLeCreer(REPERTOIRE_CODE_PHP);
    $getLastModDirBillets = connaitreDateDerniereModificationDossier(REPERTOIRE_BILLETS);
    $getLastModDirTemplate = connaitreDateDerniereModificationDossier(REPERTOIRE_TEMPLATE);
    $getLastModDirPHP = connaitreDateDerniereModificationDossier(REPERTOIRE_CODE_PHP);
    $getLastModDir = max($getLastModDirBillets, $getLastModDirPHP, $getLastModDirTemplate);
    $miseAJour=$timestampDerniereMaj - $getLastModDir < 0;
    return ($miseAJour);
}

function miseAJourTimestamp()
{
    $fichiertimestamp = fopen(NOM_FICHIER_VERIFICATION_TIMESTAMP, 'w');
    fwrite($fichiertimestamp, time());
    fclose($fichiertimestamp);
}

