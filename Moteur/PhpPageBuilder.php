<?php

/**
 * Au démarrage d'un build, il est intéressant de supprimer le build précédent pour éviter de garder des traces inutiles
 */
function nettoyageEtSetupDossier($dossierANettoyer)
{
    dossierExistantOuLeCreer($dossierANettoyer);
    nettoyageDossierDestinationIncluantSousDossier($dossierANettoyer);
}

function creationIndexBlog($dossierSource, $nomFichierEnTete, $dossierDestinationRendu)
{
    $chemin = $dossierSource . "/" . $nomFichierEnTete . '.' . "json"; // On définit le chemin du fichier à utiliser.
    if (file_exists($chemin)) {
        $json = file_get_contents($chemin);
        $json_data = json_decode($json, true);

        $titre = Constante::NOM_PAGE_ACCUEIL . " : " . Constante::NOM_DU_SITE;
        ob_start();
        ?>
        <H1 id="titre_accueil"><?php echo Constante::NOM_DU_SITE ?></H1>
        <p class="sous-titre lead"><?php echo Constante::DESCRIPTION_PAGE_ACCUEIL ?></p>
        <?php
        foreach ($json_data as $enTeteArticleBlog) {
            if (array_key_exists('titre', $enTeteArticleBlog)
                && array_key_exists('url', $enTeteArticleBlog)
                && array_key_exists('date', $enTeteArticleBlog)
                && array_key_exists('nbMots', $enTeteArticleBlog)
                && array_key_exists('categorie', $enTeteArticleBlog)) {
                ?>
                <article>
                    <header>
                        <h2>
                            <small> <a
                                    href="<?= $enTeteArticleBlog['url'] . '.' . "php" ?>"><?= $enTeteArticleBlog['titre'] ?></a>
                            </small>

                        </h2>
                        <small><?= $enTeteArticleBlog['date'] ?> • <?= $enTeteArticleBlog['categorie'] ?>
                            • <?= $enTeteArticleBlog['nbMots'] ?></small>
                    </header>
                    <p><?= $enTeteArticleBlog['description'] ?></p>
                </article>
                <?php
            }
        }
        $contenu = ob_get_clean();
        $description = Constante::DESCRIPTION_PAGE_ACCUEIL;
        ob_start();
        require(Constante::LOCALISATION_TEMPLATE);
        $corpsPage = ob_get_clean();
        $header = file_get_contents(Constante::LOCALISATION_HEADER_TEMPLATE);
        $footer = file_get_contents(Constante::LOCALISATION_FOOTER_TEMPLATE);
        $page = $header . $corpsPage . $footer;
        // index est le nom utilisé pour la première page d'un site sur la majorité des serveurs
        $fichierEnTete = fopen($dossierDestinationRendu . '/' . "index.php", 'w');
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
        if (verifierExtensionFichier($fichier, "json")) {
            $chemin = $dossierSource . "/" . $fichier; // On définit le chemin du fichier à utiliser.
            $json = file_get_contents($chemin);
            $json_data = json_decode($json, true);

            if (array_key_exists('enTete', $json_data) && array_key_exists('contenu', $json_data)) {
                $titre = $json_data['enTete']['titre'] . " : " . Constante::NOM_DU_SITE;
                $categorie = $json_data['enTete']['categorie'];

                if ($categorie != null) {
                    $categorie = " • " . $categorie;
                }
                $sousTitre = "";
                if ($json_data['enTete']['formatArticle']) {
                    $sousTitre = "<small>" . $json_data['enTete']['date'] . $categorie . " • " . $json_data['enTete']['nbMots'] . "</small>";
                } else if ($json_data['enTete']['formatPage']) {
                    $sousTitre = "";
                }

                ob_start();
                ?>
                <H2 id="titre_accueil">
                    <a href=<?php echo '"' . Constante::ADRESSE_EXACTE_SITE . '"' ?>><?php echo Constante::NOM_DU_SITE ?></a>
                </H2>
                <p class="sous-titre lead"><?php echo Constante::DESCRIPTION_PAGE_ACCUEIL ?></p>
                <article>
                    <header>
                        <h1><?= $json_data['enTete']['titre'] ?></h1>
                        <?= $sousTitre ?>
                    </header>
                    <p><?= $json_data['contenu'] ?></p>
                </article>
                <?php

                $contenu = ob_get_clean();
                ob_start();
                $description = $json_data['enTete']['description'];
                require(Constante::LOCALISATION_TEMPLATE);
                $corpsPage = ob_get_clean();
                $header = file_get_contents(Constante::LOCALISATION_HEADER_TEMPLATE);
                $footer = file_get_contents(Constante::LOCALISATION_FOOTER_TEMPLATE);
                $page = $header . $corpsPage . $footer;
                $fichierEnTete = fopen($dossierDestinationRendu . '/' . $json_data['enTete']['url'] . '.' . "php", 'w');
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
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '
            <urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
            xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
    $xml .= '
                        <url>
                            <loc>' . Constante::ADRESSE_EXACTE_SITE . '/' . "index.php" . '</loc>
                        </url>';
    $dossiersSource = array(
        $dossierSourceArticle,
        $dossierSourceErreur
    );
    foreach ($dossiersSource as $dossierSource) {
        $fichierEnTete = $dossierSource . "/" . $nomFichierEnTete . '.' . "json"; // On définit le chemin du fichier à utiliser.
        if (file_exists($fichierEnTete)) {
            $json = file_get_contents($fichierEnTete);
            $json_data = json_decode($json, true);
            // Création du fichier sitemap

            foreach ($json_data as $enTeteArticleBlog) {
                if (array_key_exists('titre', $enTeteArticleBlog) && array_key_exists('url', $enTeteArticleBlog) && array_key_exists('date', $enTeteArticleBlog) && array_key_exists('nbMots', $enTeteArticleBlog)) {

                    $xml .= '
                        <url>
                            <loc>' . Constante::ADRESSE_EXACTE_SITE . '/' . $enTeteArticleBlog['url'] . "." . "php" . '</loc>
                        </url>';
                }
            }
        }
    }
    $xml .= '</urlset>';
    // index est le nom utilisé pour la première page d'un site sur la majorité des serveurs
    $fichierSitemap = fopen($dossierDestinationRendu . '/' . Constante::NOM_FICHIER_SITEMAP, 'w');
    fwrite($fichierSitemap, $xml);
    fclose($fichierSitemap);
    return null;
}

function creationHtaccess($dossierDestinationRendu)
{
    $htaccess = "";
    if (Constante::REDIRECTION_HTTPS) {
        $htaccess .= "RewriteEngine On";
        $htaccess .= "\nRewriteCond %{SERVER_PORT} 80";
        $htaccess .= "\nRewriteRule ^(.*)$ " . Constante::ADRESSE_EXACTE_SITE . "/$1 [R=301,L]";
    }
    $htaccess .= "\n" . '<IfModule mod_headers.c>
Header always set X-FRAME-OPTIONS "DENY"
</IfModule>

<IfModule mod_headers.c>
Header always set X-XSS-Protection "1; mode=block"
</IfModule>

<IfModule mod_headers.c>
Header set Content-Security-Policy "script-src \'self\' https://www.google.com"
</IfModule>

<IfModule mod_headers.c>
Header always set X-Content-Type-Options "nosniff"
</IfModule>

<IfModule mod_deflate.c>
AddOutputFilterByType DEFLATE text/plain
AddOutputFilterByType DEFLATE text/html
AddOutputFilterByType DEFLATE text/xml
AddOutputFilterByType DEFLATE text/shtml
AddOutputFilterByType DEFLATE text/css
AddOutputFilterByType DEFLATE application/xml
AddOutputFilterByType DEFLATE application/xhtml+xml
AddOutputFilterByType DEFLATE application/rss+xml
AddOutputFilterByType DEFLATE application/javascript
AddOutputFilterByType DEFLATE application/x-javascript
</IfModule> ';

    $adresseErreur404 = $dossierDestinationRendu . '/' . "404.php";
    $adresseErreur403 = $dossierDestinationRendu . '/' . "403.php";

    if (file_exists($adresseErreur404)) {
        $htaccess .= "\nErrorDocument 404 " . Constante::ADRESSE_EXACTE_SITE . "/" . "404.php";
    }
    if (file_exists($adresseErreur403)) {
        $htaccess .= "\nErrorDocument 403 " . Constante::ADRESSE_EXACTE_SITE . "/" . "403.php";
    }

    // index est le nom utilisé pour la première page d'un site sur la majorité des serveurs
    $fichierHtaccess = fopen($dossierDestinationRendu . '/.htaccess', 'w');
    fwrite($fichierHtaccess, $htaccess);
    fclose($fichierHtaccess);
    return null;
}

function creationRobotsTxT($dossierDestinationRendu)
{
    // Création du fichier robots.txt
    // Choix du user-agent
    $robotsTxT = "User-agent: *" . "\n";
    // On donne l'adresse du fichier sitemap
    $robotsTxT .= "Sitemap :" . Constante::ADRESSE_EXACTE_SITE . "/" . Constante::NOM_FICHIER_SITEMAP . "\n";
    $robotsTxT .= "Allow:/" . "\n";

    // Suppression à l'indexation de tous les répertoires autres que celui du rendu
    $dir = "../";
    // si le dossier racine existe (ce qui semble évident) et qu'il contient quelque chose
    if (is_dir($dir) && $dh = opendir($dir)) {
        // boucler tant que quelque chose est trouve
        while (($file = readdir($dh)) !== false) {

            // affiche le nom et le type si ce n'est pas un element du systeme
            if (is_dir($dir . $file) && $file != '.' && $file != '..' && $file != $dossierDestinationRendu) {
                // on interdit le parcours de tous les dossiers hormis celui de rendu
                $robotsTxT .= "Disallow: /" . $file . "/" . "\n";
            }
        }
        // on ferme la connection
        closedir($dh);
    }
    // Le nom du fichier robots.txt est imposé et standard
    $fichierRobotsTxT = fopen($dossierDestinationRendu . '/' . 'robots.txt', 'w');
    fwrite($fichierRobotsTxT, $robotsTxT);
    fclose($fichierRobotsTxT);
    return null;
}
