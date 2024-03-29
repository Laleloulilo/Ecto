<?php

function traiterRepertoireJsonArticleMarkdown($dossierSource, $dossierDestinationRendu, $estDossierPageErreur = false, $estPage = false)
{
    // Nettoyage de la destination
    dossierExistantOuLeCreer($dossierDestinationRendu);
    nettoyageDossierDestinationHorsSousDossier($dossierDestinationRendu);
    // Listing des fichiers à traiter.
    $repertoire = opendir($dossierSource); // On définit le répertoire dans lequel on souhaite travailler.
    while (false !== ($nomItem = readdir($repertoire))) {

        if ($nomItem != '.' && $nomItem != '..' && is_dir($dossierSource . '/' . $nomItem)) {
            //Si un fichier MD est présent dans un sous-répertoire, ce sous répertoire deviendra sa catégorie
            //Ecto ne prend pas en charge les repertoires enfants multiples, seul le premier niveau est analysé
            $dossierEnfant = $dossierSource . '/' . $nomItem;
            $repertoireEnfant = opendir($dossierEnfant); // On définit le répertoire dans lequel on souhaite travailler.
            while (false !== ($nomEnfant = readdir($repertoireEnfant))) {
                controlerEtFormaterJsonArticleMarkdown($nomEnfant, $dossierEnfant, $dossierDestinationRendu, $nomItem, $estPage, $estDossierPageErreur);
            }
            closedir($repertoireEnfant);
        } else {
            controlerEtFormaterJsonArticleMarkdown($nomItem, $dossierSource, $dossierDestinationRendu, CATEGORIE_PAR_DEFAUT, $estPage, $estDossierPageErreur);
        }
    }
    closedir($repertoire);
}

function controlerEtFormaterJsonArticleMarkdown($nomFichier, $dossierSource, $dossierDestinationRendu, $categorie, $estPage, $EstDossierPageErreur = false)
{
    $Parsedown = new ParsedownExtra();

    if (verifierExtensionFichier($nomFichier, 'md')) {
        //Définition de différentes variables
        $encodageUtilise = 'UTF-8';
        $article = null;
        //Initialisation des entrants
        $timestampExact = null;
        //Contenu du fichier à manipuler
        $contenu_du_fichier = null;
        $titre = null;
        $description = null;
        $url = null;
        $nbMots = null;

        $fichierAManipuler = $dossierSource . '/' . $nomFichier;
        $contenu_du_fichier = file_get_contents($fichierAManipuler);
        $fichiervalide = verifierNombreBlocDansFichier($contenu_du_fichier, NOMBRE_BLOC_FICHIER_MARKDOWN, DELIMITEUR_BLOCS_MARKDOWN);
        //Vérification et mise en forme des entrants d'en-tête
        $fichierSource = fopen($fichierAManipuler, 'r');
        $limite = 2;
        while ($fichiervalide && $limite > 0) {
            $ligne = fgets($fichierSource);
            // décodage de l'en-tête
            if (trim($ligne) === DELIMITEUR_BLOCS_MARKDOWN
            ) {
                $limite--;
            } elseif ($limite = 1 && stripos($ligne, ':')) { //on vérifie que nous sommes bien dans un bloc d'en-tête avec limite=1
                $pieces = explode(':', $ligne, 2);
                switch ($pieces[0]) {
                    case 'titre':
                        $titre = mb_convert_encoding(trim($pieces[1]), $encodageUtilise, $encodageUtilise);
                        break;
                    case 'date':
                        // Uniformisation de la date
                        // parfois strtotime présente un bug de transcription de date, dans ce cas on remplace les / par des tirets
                        $timestampExact = strtotime(str_replace('/', '-', trim($pieces[1])));
                        break;
                    case 'description':
                        $description = mb_convert_encoding(trim($pieces[1]), $encodageUtilise, $encodageUtilise);
                        break;
                    default :
                        break;
                }
            }
        }

        if (controlesBloquantEnTeteJsonArticleMarkdown($titre, $timestampExact, $description, $estPage, $EstDossierPageErreur)) {
            //Parfois les fichiers embarquent plusieurs '---' car celui ci est utilisé pour tracer des lignes en markdown, on prend en compte ce cas
            $contenuMD = explode(DELIMITEUR_BLOCS_MARKDOWN, $contenu_du_fichier, NOMBRE_BLOC_FICHIER_MARKDOWN + 1);
            $articleAParser = mb_convert_encoding($contenuMD[2], $encodageUtilise, $encodageUtilise);
            // Conversion du contenu en HTML var_dump($contenuMD)
            $articleParse = $Parsedown->text($articleAParser);
            $contenuEditorial = trim($articleParse);
            $contenuEditorial = correctionCheminImage($contenuEditorial, REPERTOIRE_IMAGE);
            // Rajouts des valeurs calculées à l'en-tête
            $nbMots = calculInformationLongueurLecture($articleParse);
            $url = transformerTitreEnUrlValide($titre);
            // création de l'en-tête
            $entete = new EnTete($titre, $description, $url, $nbMots, $timestampExact, $categorie, $estPage);
            // Création de l'obet article
            $article = new Article($entete, $contenuEditorial);
            // Enregistrement de l'article
            $fichierArticle = fopen($dossierDestinationRendu . '/' . $url . '.' . 'json', 'w');
            fwrite($fichierArticle, json_encode($article));
            fclose($fichierArticle);
        }
    }
}

function controlesBloquantEnTeteJsonArticleMarkdown($titre, $timestampExact, $description, $estPage, $estDossierPageErreur = false)
{
    $infoIncompletes = empty($titre) || empty($timestampExact) || empty($description) || !is_bool($estPage);
    if (!$infoIncompletes) {
        if (!in_array($titre, ERREUR_AUTORISES)) {
            // Les titres des pages d'erreurs sont réservés aux pages d'erreurs.
            return !$estDossierPageErreur;
        }
        return true;
    } else {
        return false;
    }
}

function creerListingEntete($dossierDestination)
{
    $listeEnTete = array();
    $repertoire = opendir($dossierDestination);
    while (false !== ($fichier = readdir($repertoire))) // On lit chaque fichier du répertoire dans la boucle.
    {
        if (verifierExtensionFichier($fichier, 'json')) {
            $chemin = $dossierDestination . '/' . $fichier; // On définit le chemin du fichier à utiliser.
            $json = file_get_contents($chemin);
            $json_data = json_decode($json, true);
            if (array_key_exists('enTete', $json_data)) {
                $enTete = $json_data['enTete'];

                //Rajout de l'en-tete de l'article à la liste des en-tête
                $listeEnTete[] = $enTete;
            }
        }
    }
    $fichierEnTete = fopen($dossierDestination . '/en-tete.json', 'w');
    $colonne = array_column($listeEnTete, 'timestamp_date');
    // On ordonne le fichier d'en-tête par date
    array_multisort($colonne, SORT_DESC, $listeEnTete);
    fwrite($fichierEnTete, json_encode($listeEnTete));
    fclose($fichierEnTete);
    closedir($repertoire);
    return null;
}

