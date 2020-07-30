<?php
require_once('Parsedown.php');
require_once('ParsedownExtra.php');
require_once('Constante.php');
require_once('Entites/EnTete.php');
require_once('Entites/Article.php');
require_once('JsonDataBaseBuilder.php');
require_once('PhpPageBuilder.php');
require_once('GenericTools.php');

function processusGlobalGenerationSite()
{
    if (analyseRefraichissementDelaiMiseAJourDonnee()) {
        echo MODE_DEBUG === true ? "Vide les dossiers issus du build précédent <br/>" : null;
        nettoyageEtSetupDossier(REPERTOIRE_BUILD);
        nettoyageEtSetupDossier(REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR);
        nettoyageEtSetupDossier(REPERTOIRE_DESTINATION_JSON);
        nettoyageEtSetupDossier(REPERTOIRE_DESTINATION_RENDU_PHP);
        echo MODE_DEBUG === true ? "On formate les pages d'erreur en json <br/>" : null;
        traiterRepertoireJsonArticleMarkdown(REPERTOIRE_PAGES_ERREUR, REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR, true, true);
        echo MODE_DEBUG === true ? "On formate les articles en json <br/>" : null;
        traiterRepertoireJsonArticleMarkdown(REPERTOIRE_BILLETS, REPERTOIRE_DESTINATION_JSON);
        echo MODE_DEBUG === true ? "On crée le listing des articles <br/>" : null;
        creerListingEntete(REPERTOIRE_DESTINATION_JSON);
        echo MODE_DEBUG === true ? "On crée le listing des pages d'erreur <br/>" : null;
        creerListingEntete(REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR);
        echo MODE_DEBUG === true ? "Mise en place des templates <br/>" : null;
        copierDossierEtSousDossier(DOSSIER_ELEMENTS_DESIGN_TEMPLATE, REPERTOIRE_DESTINATION_RENDU_PHP);
        echo MODE_DEBUG === true ? "Mise en place des images <br/>" : null;
        dossierExistantOuLeCreer(REPERTOIRE_CONTENU_IMAGE);
        nettoyageEtSetupDossier(REPERTOIRE_RENDU_IMAGE);
        copierDossierEtSousDossier(REPERTOIRE_CONTENU_IMAGE, REPERTOIRE_RENDU_IMAGE);
        echo MODE_DEBUG === true ? "On crée les rendus articles <br/>" : null;
        rendufichiersArticle(REPERTOIRE_DESTINATION_JSON, REPERTOIRE_DESTINATION_RENDU_PHP);
        echo MODE_DEBUG === true ? "On crée les rendus pages d'erreur <br/>" : null;
        rendufichiersArticle(REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR, REPERTOIRE_DESTINATION_RENDU_PHP);
        echo MODE_DEBUG === true ? "On crée l'index <br/>" : null;
        creationIndexBlog(REPERTOIRE_DESTINATION_JSON, 'en-tete', REPERTOIRE_DESTINATION_RENDU_PHP);
        echo MODE_DEBUG === true ? "On crée le sitemap <br/>" : null;
        creationSitemap(REPERTOIRE_DESTINATION_JSON, REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR, 'en-tete', REPERTOIRE_DESTINATION_RENDU_PHP);
        echo MODE_DEBUG === true ? "On crée le fichier robots.txt <br/>" : null;
        creationRobotsTxT(REPERTOIRE_DESTINATION_RENDU_PHP);
        echo MODE_DEBUG === true ? "On crée le fichier .htaccess <br/>" : null;
        creationHtaccess(REPERTOIRE_DESTINATION_RENDU_PHP);
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
    return ($timestampDerniereMaj - $getLastModDir < 0);
}


function miseAJourTimestamp()
{
    $fichiertimestamp = fopen(NOM_FICHIER_VERIFICATION_TIMESTAMP, 'w');
    fwrite($fichiertimestamp, time());
    fclose($fichiertimestamp);
}

