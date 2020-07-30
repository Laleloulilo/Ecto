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
    $enTeteGeneriqueSourceImage ='src="./';
    return $contenu = str_replace($enTeteGeneriqueSourceImage, $enTeteGeneriqueSourceImage . $cheminDossierImage . '/', $contenu);
}

function formaterDateArticle($dateAFormater)
{
    setlocale(LC_TIME, ZONE_TEMPORELLE_HEURE);
    // le format actuel est JJ Mois AAAA
    $timestampFormate = strftime('%e %B %G', $dateAFormater);
    // Première lettre de chaque mot en majuscule.
    return ucwords($timestampFormate);
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

function dossierExistantOuLeCreer($path)
{
    if (is_dir($path)) {
        return true;
    } else {
        return mkdir($path);
    }
}

function copierDossierEtSousDossier($origine, $destination)
{
    $test = scandir($origine);

    $file = 0;
    $file_tot = 0;

    foreach ($test as $val) {
        if ($val != '.' && $val != '..') {
            if (is_dir($origine . '/' . $val)) {
                dossierExistantOuLeCreer($destination . '/' . $val);
                copierDossierEtSousDossier($origine . '/' . $val, $destination . '/' . $val);
            } else {
                $file_tot++;
                if (copy($origine . '/' . $val, $destination . '/' . $val)) {
                    $file++;
                } else if (!file_exists($origine . '/' . $val)) {
                    echo $origine . '/' . $val;
                }
            }
        }
    }
    return true;
}

function nettoyageDossierDestinationIncluantSousDossier($directory, $empty = true)
{
    if (substr($directory, -1) == '/') {
        $directory = substr($directory, 0, -1);
    }
    if (!file_exists($directory) || !is_dir($directory) || !is_readable($directory)) {
        return false;
    } else {
        $directoryHandle = opendir($directory);
        while ($contents = readdir($directoryHandle)) {
            if ($contents != '.' && $contents != '..') {
                $path = $directory . '/' . $contents;

                if (is_dir($path)) {
                    nettoyageDossierDestinationIncluantSousDossier($path);
                } else {
                    unlink($path);
                }
            }
        }
        closedir($directoryHandle);
        if (!$empty && !rmdir($directory)) {
            return false;
        }
        return true;
    }
}

function connaitreDateDerniereModificationDossier($dossier)
{
    $iterator = new DirectoryIterator($dossier);

    $mtime = 0;
    foreach ($iterator as $fileinfo) {
        if ($fileinfo->isFile() && $fileinfo->getMTime() > $mtime) {
            $mtime = $fileinfo->getMTime();
        }else if($fileinfo->isDir() && $fileinfo->getFilename()!='..' && $fileinfo->getFilename()!='.' ){
            $tempsdossier = connaitreDateDerniereModificationDossier($dossier.'/'.$fileinfo->getFilename());
            $mtime = max($mtime,$tempsdossier);
        }
    }
    return $mtime;
}

