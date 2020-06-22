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
        echo Constante::MODE_DEBUG === true ? "Vide les dossiers issus du build précédent <br/>" : null;
        nettoyageEtSetupDossier(Constante::REPERTOIRE_BUILD);
        nettoyageEtSetupDossier(Constante::REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR);
        nettoyageEtSetupDossier(Constante::REPERTOIRE_DESTINATION_JSON);
        nettoyageEtSetupDossier(Constante::REPERTOIRE_DESTINATION_RENDU_PHP);
        echo Constante::MODE_DEBUG === true ? "On formate les pages d'erreur en json <br/>" : null;
        traiterRepertoireJsonArticleMarkdown(Constante::REPERTOIRE_PAGES_ERREUR, Constante::REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR, true, true);
        echo Constante::MODE_DEBUG === true ? "On formate les articles en json <br/>" : null;
        traiterRepertoireJsonArticleMarkdown(Constante::REPERTOIRE_BILLETS, Constante::REPERTOIRE_DESTINATION_JSON);
        echo Constante::MODE_DEBUG === true ? "On crée le listing des articles <br/>" : null;
        creerListingEntete(Constante::REPERTOIRE_DESTINATION_JSON);
        echo Constante::MODE_DEBUG === true ? "On crée le listing des pages d'erreur <br/>" : null;
        creerListingEntete(Constante::REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR);
        echo Constante::MODE_DEBUG === true ? "Mise en place des templates <br/>" : null;
        copierDossierEtSousDossier(Constante::DOSSIER_ELEMENTS_DESIGN_TEMPLATE, Constante::REPERTOIRE_DESTINATION_RENDU_PHP);
        echo Constante::MODE_DEBUG === true ? "Mise en place des images <br/>" : null;
        dossierExistantOuLeCreer(Constante::REPERTOIRE_CONTENU_IMAGE);
        nettoyageEtSetupDossier(Constante::REPERTOIRE_RENDU_IMAGE);
        copierDossierEtSousDossier(Constante::REPERTOIRE_CONTENU_IMAGE, Constante::REPERTOIRE_RENDU_IMAGE);
        echo Constante::MODE_DEBUG === true ? "On crée les rendus articles <br/>" : null;
        rendufichiersArticle(Constante::REPERTOIRE_DESTINATION_JSON, Constante::REPERTOIRE_DESTINATION_RENDU_PHP);
        echo Constante::MODE_DEBUG === true ? "On crée les rendus pages d'erreur <br/>" : null;
        rendufichiersArticle(Constante::REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR, Constante::REPERTOIRE_DESTINATION_RENDU_PHP);
        echo Constante::MODE_DEBUG === true ? "On crée l'index <br/>" : null;
        creationIndexBlog(Constante::REPERTOIRE_DESTINATION_JSON, 'en-tete', Constante::REPERTOIRE_DESTINATION_RENDU_PHP);
        echo Constante::MODE_DEBUG === true ? "On crée le sitemap <br/>" : null;
        creationSitemap(Constante::REPERTOIRE_DESTINATION_JSON, Constante::REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR, 'en-tete', Constante::REPERTOIRE_DESTINATION_RENDU_PHP);
        echo Constante::MODE_DEBUG === true ? "On crée le fichier robots.txt <br/>" : null;
        creationRobotsTxT(Constante::REPERTOIRE_DESTINATION_RENDU_PHP);
        echo Constante::MODE_DEBUG === true ? "On crée le fichier .htaccess <br/>" : null;
        creationHtaccess(Constante::REPERTOIRE_DESTINATION_RENDU_PHP);
    }
}

function analyseRefraichissementDelaiMiseAJourDonnee()
{
    $mettreAjourTimestampEnregistre = false;
    $miseAJourAFaire = false;
    $timestamp = 0;
    if (file_exists(Constante::NOM_FICHIER_VERIFICATION_TIMESTAMP)) {
        if (file_exists(Constante::NOM_FICHIER_VERIFICATION_TIMESTAMP)) {
            $timestamp = file_get_contents(Constante::NOM_FICHIER_VERIFICATION_TIMESTAMP);
        }
        if (!is_int($timestamp)) {
            if (abs(time() - $timestamp) > Constante::NOMBRE_DE_SECONDES_AVANT_MISE_A_JOUR) {
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
    dossierExistantOuLeCreer(Constante::REPERTOIRE_BILLETS);
    dossierExistantOuLeCreer(Constante::REPERTOIRE_TEMPLATE);
    dossierExistantOuLeCreer(Constante::REPERTOIRE_CODE_PHP);
    $getLastModDirBillets = connaitreDateDerniereModificationDossier(Constante::REPERTOIRE_BILLETS);
    $getLastModDirTemplate = connaitreDateDerniereModificationDossier(Constante::REPERTOIRE_TEMPLATE);
    $getLastModDirPHP = connaitreDateDerniereModificationDossier(Constante::REPERTOIRE_CODE_PHP);
    $getLastModDir = max($getLastModDirBillets, $getLastModDirPHP, $getLastModDirTemplate);
    return ($timestampDerniereMaj - $getLastModDir < 0);
}


function miseAJourTimestamp()
{
    $fichiertimestamp = fopen(Constante::NOM_FICHIER_VERIFICATION_TIMESTAMP, 'w');
    fwrite($fichiertimestamp, time());
    fclose($fichiertimestamp);
}

