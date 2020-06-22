<?php

function traiterRepertoireJsonArticleMarkdown($dossierSource, $dossierDestinationRendu, $EstDossierPageErreur = false)
{
  // Nettoyage de la destination
    echo Constante::MODE_DEBUG === true ? "Je check si le dossier existe. <br>" : null;
  dossierExistantOuLeCreer($dossierDestinationRendu);
    echo Constante::MODE_DEBUG === true ? "Le dossier a été créé. <br>" : null;
  nettoyageDossierDestinationHorsSousDossier($dossierDestinationRendu);
  // Listing des fichiers à traiter.
  $repertoire = opendir($dossierSource); // On définit le répertoire dans lequel on souhaite travailler.
  while (false !== ($nomFichier = readdir($repertoire))) {
    controlerEtFormaterJsonArticleMarkdown($nomFichier, $dossierSource, $dossierDestinationRendu, $EstDossierPageErreur);
  }
  closedir($repertoire); // Ne pas oublier de fermer le dossier ***EN DEHORS de la boucle*** ! Ce qui évitera à PHP beaucoup de calculs et des problèmes liés à l'ouverture du dossier.
}

function controlerEtFormaterJsonArticleMarkdown($nomFichier, $dossierSource, $dossierDestinationRendu, $EstDossierPageErreur = false)
{
  $Parsedown = new ParsedownExtra();

  if (verifierExtensionFichier($nomFichier, "md")) {
    //Définition de différentes variables
    $encodageUtilise = 'UTF-8';
    $article = null;
    //Initialisation des entrants
    $timestampExact = null;
    $timestampFormate = null;
    //Contenu du fichier à manipuler
    $contenu_du_fichier = null;
    $titre = null;
    $description = null;
    $url = null;
    $nbMots = null;
    //Cas particulier des pages d'erreur
    if ($EstDossierPageErreur) {
      $timestampExact = time();
      $description = "page d'erreur";
    }
    $fichierAManipuler = $dossierSource . '/' . $nomFichier;
    $contenu_du_fichier = file_get_contents($fichierAManipuler);
    $fichiervalide = verifierNombreBlocDansFichier($contenu_du_fichier, Constante::NOMBRE_BLOC_FICHIER_MARKDOWN, Constante::DELIMITEUR_BLOCS_MARKDOWN);
    //Vérification et mise en forme des entrants d'en-tête
    $fichierSource = fopen($fichierAManipuler, 'r');
    $limite = 2;
    while ($fichiervalide && $limite > 0) {
      $ligne = fgets($fichierSource);
      // décodage de l'en-tête
      if (trim($ligne) === Constante::DELIMITEUR_BLOCS_MARKDOWN
      ) {
        $limite--;
      } elseif ($limite = 1 && stripos($ligne, ":")) { //on vérifie que nous sommes bien dans un bloc d'en-tête avec limite=1
        $pieces = explode(":", $ligne, 2);
        switch ($pieces[0]) {
          case "titre":
            $titre = mb_convert_encoding(trim($pieces[1]), $encodageUtilise, $encodageUtilise);
            break;
          case "date":
            // Uniformisation de la date
            $timestampExact = strtotime(trim($pieces[1]));
              //parfois strtotime présente un bug de transcription de date, dans ce cas on remplace les / par des tirets
              if ($timestampExact === FALSE) {
                  $timestampExact = strtotime(str_replace('/', '-', trim($pieces[1])));
              }
            break;
          case "description":
            $description = mb_convert_encoding(trim($pieces[1]), $encodageUtilise, $encodageUtilise);
            break;
        }
      }
    }
    if (controlesBloquantEnTeteJsonArticleMarkdown($titre, $timestampExact, $description, $EstDossierPageErreur)) {
      //Parfois les fichiers embarquent plusieurs "---" car celui ci est utilisé pour tracer des lignes en markdown, on prend en compte ce cas
      $contenuMD = explode(Constante::DELIMITEUR_BLOCS_MARKDOWN, $contenu_du_fichier, Constante::NOMBRE_BLOC_FICHIER_MARKDOWN + 1);
      $articleAParser = mb_convert_encoding($contenuMD[2], $encodageUtilise, $encodageUtilise);
      // Conversion du contenu en HTML var_dump($contenuMD)
      $articleParse = $Parsedown->text($articleAParser);
      $contenuEditorial = trim($articleParse);
      $contenuEditorial = correctionCheminImage($contenuEditorial, Constante::REPERTOIRE_IMAGE);
      // Rajouts des valeurs calculées à l'en-tête
      $nbMots = calculInformationLongueurLecture($articleParse);
      $timestampFormate = formaterDateArticle($timestampExact);
      $url = transformerTitreEnUrlValide($titre);
      // création de l'en-tête
      $entete = new EnTete($timestampFormate, $titre, $description, $url, $nbMots, $timestampExact);
      // Création de l'obet article
      $article = new Article($entete, $contenuEditorial);
      // Enregistrement de l'article
      $fichierArticle = fopen($dossierDestinationRendu . '/' . $url . '.' . "json", 'w');
      fwrite($fichierArticle, json_encode($article, JSON_THROW_ON_ERROR));
      fclose($fichierArticle);
    }
  }
}

function controlesBloquantEnTeteJsonArticleMarkdown($titre, $timestampExact, $description, $EstDossierPageErreur = false)
{
  if ($EstDossierPageErreur && $titre != "404" && $titre != "403") {
    //Dans le cas d'une page d'erreur seules certains titres sont pertinents (404,403 dans un premier temps)
    return false;
  }
  if (empty($titre) || empty($timestampExact) || empty($description)) {
    return false;
  }
  return true;
}

function creerListingEntete($dossierDestination)
{
  $listeEnTete = array();
  $repertoire = opendir($dossierDestination); // On définit le répertoire dans lequel on souhaite travailler.
  while (false !== ($fichier = readdir($repertoire))) // On lit chaque fichier du répertoire dans la boucle.
  {
    if (verifierExtensionFichier($fichier, "json")) {
      $chemin = $dossierDestination . "/" . $fichier; // On définit le chemin du fichier à utiliser.
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
  $resultat = array_multisort($colonne, SORT_DESC, $listeEnTete);
  if (!$resultat) {
    error_log("On a un gros problème à la génération du json");
  }
  fwrite($fichierEnTete, json_encode($listeEnTete));
  fclose($fichierEnTete);
  closedir($repertoire); // Ne pas oublier de fermer le dossier ***EN DEHORS de la boucle*** ! Ce qui évitera à PHP beaucoup de calculs et des problèmes liés à l'ouverture du dossier.
  return null;
}

