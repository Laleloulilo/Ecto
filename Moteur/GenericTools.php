<?php

function nettoyageDossierDestinationHorsSousDossier($dossierDestination)
{
    $repertoire = opendir($dossierDestination); // On définit le répertoire dans lequel on souhaite travailler.
    while (false !== ($fichier = readdir($repertoire))) // On lit chaque fichier du répertoire dans la boucle.
    {
        $chemin = $dossierDestination . '/' . $fichier; // On définit le chemin du fichier à effacer.

        // Si le fichier n'est pas un répertoire…
        if ($fichier != '..' && $fichier != '.' && !is_dir($fichier)) {
            unlink($chemin); // On efface.
        }
    }
    closedir($repertoire); // Ne pas oublier de fermer le dossier ***EN DEHORS de la boucle*** ! Ce qui évitera à PHP beaucoup de calculs et des problèmes liés à l'ouverture du dossier.
    return null;
}

function calculInformationLongueurLecture($contenu_article)
{
    $chaine_retour = '';
    // Arrondi à l'entier supérieur du nombre de minutes nécessaires pour lire l'article
    $minute_lecture = ceil(str_word_count(strip_tags($contenu_article)) / MOT_PAR_MINUTE);
    // Si l'utilisateur à choisi d'assortir son temps de lecture d'emji, on en rajoute.
    if (EMOJI_LONGUEUR_LECTURE) {
        $compteur = $minute_lecture;
        if ($compteur < 25) {
            // Cas des articles longs, une emoji par tranche de 5 minutes
            while ($compteur > 0) {
                $chaine_retour .= EMOJI_5_MINUTES;
                $compteur = $compteur - 5;
            }
            $chaine_retour .= ' ';
        } else {
            // Cas des articles longs, une emoji par tranche de 10 minutes
            while ($compteur > 0) {
                $chaine_retour .= EMOJI_10_MINUTES;
                $compteur = $compteur - 10;
            }
            $chaine_retour .= ' ';
        }
    }
    return $chaine_retour .= $minute_lecture . ' minute' . ($minute_lecture > 1 ? 's' : '');
}

function correctionCheminImage($contenu, $cheminDossierImage)
{
    $enTeteGeneriqueSourceImage = 'src="./';
    return $contenu = str_replace($enTeteGeneriqueSourceImage, $enTeteGeneriqueSourceImage . $cheminDossierImage . '/', $contenu);
}

function verifierExtensionFichier($fichierAControler, $extensionSouhaitee)
{
    $info = new SplFileInfo($fichierAControler);
    $extension = $info->getExtension();
    return ($extension == $extensionSouhaitee);
}

function transformerTitreEnUrlValide($titre, $charset = 'utf-8')
{
    $titre = trim($titre);
    $titre = htmlentities($titre, ENT_NOQUOTES, $charset);
    $titre = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $titre);
    $titre = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $titre);
    $titre = preg_replace('#&[^;]+;#', '', $titre);
    $titre = preg_replace('#([^.a-z0-9]+)#i', '-', $titre);
    $titre = preg_replace('#-{2,}#', '-', $titre);
    $titre = preg_replace('#-$#', '', $titre);
    $titre = preg_replace('#^-#', '', $titre);
    return urlencode($titre);
}

function verifierNombreBlocDansFichier($contenuFichierAAnalyser, $nombreDeBlocSouhaite, $delimiteur)
{
    $contenuMD = explode($delimiteur, $contenuFichierAAnalyser);
    return count($contenuMD) >= $nombreDeBlocSouhaite + 1;
}

function dossierExistantOuLeCreer($chemin)
{
    if (is_dir($chemin)) {
        return true;
    } else {
        return mkdir($chemin);
    }
}

function copierDossierEtSousDossier($origine, $destination)
{
    $contenuDossierOrigine = scandir($origine);

    foreach ($contenuDossierOrigine as $elementOrigine) {
        if ($elementOrigine != '.' && $elementOrigine != '..') {
            if (is_dir($origine . '/' . $elementOrigine)) {
                dossierExistantOuLeCreer($destination . '/' . $elementOrigine);
                copierDossierEtSousDossier($origine . '/' . $elementOrigine, $destination . '/' . $elementOrigine);
            } else {
                $copieReussie=copy($origine . '/' . $elementOrigine, $destination . '/' . $elementOrigine);
                if (!$copieReussie) {
                    Logger::error("Problème lors de la copie du fichier : ".$origine . '/' . $elementOrigine);
                }
            }
        }
    }
    return true;
}

function viderDossierDestinationIncluantSousDossier($dossier)
{
    if (substr($dossier, -1) == '/') {
        $dossier = substr($dossier, 0, -1);
    }
    if (!is_readable($dossier) || !is_dir($dossier)) {
        return false;
    } else {
        $dossierOuvert = opendir($dossier);
        while ($contenuDossier = readdir($dossierOuvert)) {
            if ($contenuDossier != '.' && $contenuDossier != '..') {
                $chemin = $dossier . '/' . $contenuDossier;
                if (is_dir($chemin)) {
                    viderDossierDestinationIncluantSousDossier($chemin);
                } else {
                    unlink($chemin);
                }
            }
        }
        closedir($dossierOuvert);
    }
}

function connaitreDateDerniereModificationDossier($dossier)
{
    $iterateurDossier = new DirectoryIterator($dossier);
    $timestampDerniereModificationDossier = 0;
    foreach ($iterateurDossier as $fichier) {
        if ($fichier->isFile() && $fichier->getMTime() > $timestampDerniereModificationDossier) {
            $timestampDerniereModificationDossier = $fichier->getMTime();
        } else if ($fichier->isDir() && $fichier->getFilename() != '..' && $fichier->getFilename() != '.') {
            $tempsdossier = connaitreDateDerniereModificationDossier($dossier . '/' . $fichier->getFilename());
            $timestampDerniereModificationDossier = max($timestampDerniereModificationDossier, $tempsdossier);
        }
    }
    return $timestampDerniereModificationDossier;
}

