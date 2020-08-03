<?php

/**
 * Au démarrage d'un build, il est intéressant de supprimer le build précédent pour éviter de garder des traces inutiles
 */
function nettoyageEtSetupDossier($dossierANettoyer)
{
    dossierExistantOuLeCreer($dossierANettoyer);
    viderDossierDestinationIncluantSousDossier($dossierANettoyer);
}

function creationIndexBlog($dossierSource, $nomFichierEnTete, $dossierDestinationRendu)
{
    $chemin = $dossierSource . '/' . $nomFichierEnTete . '.' . 'json'; // On définit le chemin du fichier à utiliser.
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
        $page = $header . $corpsPage . $footer;
        // index est le nom utilisé pour la première page d'un site sur la majorité des serveurs
        $fichierEnTete = fopen($dossierDestinationRendu . '/' . 'index.php', 'w');
        fwrite($fichierEnTete, $page);
        fclose($fichierEnTete);
    }
    return null;
}

function rendufichiersArticle($dossierSource, $dossierDestinationRendu)
{
    $repertoire = opendir($dossierSource); // On définit le répertoire dans lequel on souhaite travailler.
    while (false !== ($fichier = readdir($repertoire))) // On lit chaque fichier du répertoire dans la boucle.
    {
        if (verifierExtensionFichier($fichier, 'json')) {
            $chemin = $dossierSource . '/' . $fichier; // On définit le chemin du fichier à utiliser.
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
                $page = $header . $corpsPage . $footer;
                $fichierEnTete = fopen($dossierDestinationRendu . '/' . $json_data['enTete']['url'] . '.' . 'php', 'w');
                fwrite($fichierEnTete, $page);
                fclose($fichierEnTete);
            }
        }
    }
    closedir($repertoire); // Ne pas oublier de fermer le dossier ***EN DEHORS de la boucle*** ! Ce qui évitera à PHP beaucoup de calculs et des problèmes liés à l'ouverture du dossier.
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
                if (array_key_exists('titre', $enTeteArticleBlog) && array_key_exists('url', $enTeteArticleBlog) && array_key_exists('date', $enTeteArticleBlog) && array_key_exists('nbMots', $enTeteArticleBlog)) {
                    array_push($listePages, $enTeteArticleBlog['url']);
                }
            }
        }
    }
    ob_start();
    require(LOCALISATION_TEMPLATE_SITEMAP);
    $xml = ob_get_clean();
    // index est le nom utilisé pour la première page d'un site sur la majorité des serveurs
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
    // index est le nom utilisé pour la première page d'un site sur la majorité des serveurs
    $fichierHtaccess = fopen($dossierDestinationRendu . '/.htaccess', 'w');
    fwrite($fichierHtaccess, $htaccess);
    fclose($fichierHtaccess);
    return null;
}

function creationRobotsTxT($dossierDestinationRendu)
{
    // Suppression à l'indexation de tous les répertoires autres que celui du rendu
    $dossier = '../';
    // si le dossier racine existe (ce qui semble évident) et qu'il contient quelque chose
    $listeRepertoiresInterdits = array();
    if (is_dir($dossier) && $dossierOuvert = opendir($dossier)) {
        // boucler tant que quelque chose est trouve
        while (($fichier = readdir($dossierOuvert)) !== false) {
            // affiche le nom et le type si ce n'est pas un element du systeme
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
    // Le nom du fichier robots.txt est imposé et standard
    $fichierRobotsTxT = fopen($dossierDestinationRendu . '/' . 'robots.txt', 'w');
    fwrite($fichierRobotsTxT, $robotsTxT);
    fclose($fichierRobotsTxT);
    return null;
}
