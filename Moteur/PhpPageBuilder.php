<?php

function nettoyageEtSetupDossier($dossierANettoyer)
{
    dossierExistantOuLeCreer($dossierANettoyer);
    viderDossierDestinationIncluantSousDossier($dossierANettoyer);
}

function creationIndexBlog($dossierSource, $nomFichierEnTete, $dossierDestinationRendu)
{
    $chemin = $dossierSource . '/' . $nomFichierEnTete . '.' . 'json';
    if (file_exists($chemin)) {
        $json = file_get_contents($chemin);
        $json_data = json_decode($json, true);
        $listeIndexArticle = array();
        foreach ($json_data as $enTeteArticleBlog) {
            if (array_key_exists('titre', $enTeteArticleBlog)
                && array_key_exists('url', $enTeteArticleBlog)
                && array_key_exists('date', $enTeteArticleBlog)
                && array_key_exists('nbMots', $enTeteArticleBlog)
                && array_key_exists('categorie', $enTeteArticleBlog)) {

                array_push($listeIndexArticle, array(
                    'titre' => $enTeteArticleBlog['titre'],
                    'url' => $enTeteArticleBlog['url'],
                    'date' => $enTeteArticleBlog['date'],
                    'nbMots' => $enTeteArticleBlog['nbMots'],
                    'categorie' => $enTeteArticleBlog['categorie'],
                    'description' => $enTeteArticleBlog['description']));
            }
        }
        ob_start();
        require(LOCALISATION_TEMPLATE_CORPS_INDEX);
        $corpsPage = ob_get_clean();
        $header = file_get_contents(LOCALISATION_HEADER_TEMPLATE);
        $footer = file_get_contents(LOCALISATION_FOOTER_TEMPLATE);
        $page = $header . tidy_repair_string ($corpsPage) . $footer;
        $fichier = fopen($dossierDestinationRendu . '/' . 'index.php', 'w');
        fwrite($fichier, $page);
        fclose($fichier);
    }
    return null;
}

function rendufichiersArticle($dossierSource, $dossierDestinationRendu)
{
    $repertoire = opendir($dossierSource);
    while (false !== ($fichier = readdir($repertoire)))
    {
        if (verifierExtensionFichier($fichier, 'json')) {
            $chemin = $dossierSource . '/' . $fichier;
            $json = file_get_contents($chemin);
            $json_data = json_decode($json, true);

            if (array_key_exists('enTete', $json_data) && array_key_exists('contenu', $json_data)) {

                //Création des variables pour insertion dans le template en décomposant le json
                extract($json_data['enTete']);
                extract($json_data);

                ob_start();
                require(LOCALISATION_TEMPLATE_CORPS_ARTICLE);
                $corpsPage = ob_get_clean();

                $header = file_get_contents(LOCALISATION_HEADER_TEMPLATE);
                $footer = file_get_contents(LOCALISATION_FOOTER_TEMPLATE);
                $page = $header . tidy_repair_string ($corpsPage) . $footer;
                $fichier = fopen($dossierDestinationRendu . '/' . $json_data['enTete']['url'] . '.' . 'php', 'w');
                fwrite($fichier, $page);
                fclose($fichier);
            }
        }
    }
    closedir($repertoire);
    return null;
}

function creationSitemap($dossierSourceArticle, $dossierSourceErreur, $nomFichierEnTete, $dossierDestinationRendu)
{
    $listePages = array();
    foreach (array($dossierSourceArticle, $dossierSourceErreur) as $dossierSource) {
        $fichierEnTete = $dossierSource . "/" . $nomFichierEnTete . '.' . "json"; // On définit le chemin du fichier à utiliser.
        if (file_exists($fichierEnTete)) {
            $json = file_get_contents($fichierEnTete);
            $json_data = json_decode($json, true);
            // Création du fichier sitemap
            foreach ($json_data as $enTeteArticleBlog) {
                if (array_key_exists('titre', $enTeteArticleBlog) &&
                    array_key_exists('url', $enTeteArticleBlog) &&
                    array_key_exists('date', $enTeteArticleBlog) &&
                    array_key_exists('nbMots', $enTeteArticleBlog)) {
                    array_push($listePages, $enTeteArticleBlog['url']);
                }
            }
        }
    }
    ob_start();
    require(LOCALISATION_TEMPLATE_SITEMAP);
    $xml = ob_get_clean();
    $fichierSitemap = fopen($dossierDestinationRendu . '/' . NOM_FICHIER_SITEMAP, 'w');
    fwrite($fichierSitemap, $xml);
    fclose($fichierSitemap);
    return null;
}

function creationHtaccess($dossierDestinationRendu)
{
    ob_start();
    require(LOCALISATION_TEMPLATE_HTACCESS);
    $htaccess = ob_get_clean();
    $fichierHtaccess = fopen($dossierDestinationRendu . '/.htaccess', 'w');
    fwrite($fichierHtaccess, $htaccess);
    fclose($fichierHtaccess);
    return null;
}

function creationRobotsTxT($dossierDestinationRendu)
{
    // Suppression à l'indexation de tous les répertoires autres que celui du rendu
    $dossier = '../';
    $listeRepertoiresInterdits = array();
    if (is_dir($dossier) && $dossierOuvert = opendir($dossier)) {
        // boucler tant que quelque chose est trouvé
        while (($fichier = readdir($dossierOuvert)) !== false) {
            // affiche le nom et le type si ce n'est pas un element du système
            if (is_dir($dossier . $fichier) && $fichier != '.' && $fichier != '..' && $fichier != $dossierDestinationRendu) {
                // on interdit le parcours de tous les dossiers hormis celui de rendu
                array_push($listeRepertoiresInterdits, $fichier);
            }
        }
        // on ferme la connection
        closedir($dossierOuvert);
    }
    ob_start();
    require(LOCALISATION_TEMPLATE_ROBOTS);
    $robotsTxT = ob_get_clean();
    // Le nom du fichier robots.txt est standard
    $fichierRobotsTxT = fopen($dossierDestinationRendu . '/' . 'robots.txt', 'w');
    fwrite($fichierRobotsTxT, $robotsTxT);
    fclose($fichierRobotsTxT);
    return null;
}
