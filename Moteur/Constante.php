<?php

/*
 * Constantes de paramétrage utilisateur
 */

//Gestion de la vitesse de lecture moyenne
define('MOT_PAR_MINUTE', 200);
//Gestion des titres
define('NOM_DU_SITE', 'Ecto');
define('NOM_PAGE_ACCUEIL', 'Accueil');
define('DESCRIPTION_PAGE_ACCUEIL', 'Mini-CMS, 0 dépendance, - de 100 ko, Markdown, Déploiement Copier/coller');
//Gestion du sitemap
define('ADRESSE_EXACTE_SITE', 'http://localhost/Ecto/Rendu');
// La constante EMOJI_LONGUEUR_LECTURE est utilisée pour savoir si le temps de lecture sera accompagné d'emoji
define('EMOJI_LONGUEUR_LECTURE', true);
define('EMOJI_5_MINUTES', '☕️');
define('EMOJI_10_MINUTES', '🍻');
// Gestion de la mise à jour d'Ecto
define('NOMBRE_DE_SECONDES_AVANT_MISE_A_JOUR', 0);

/*
 * Constantes techniques
 */

define('MODE_DEBUG', false);
define('REDIRECTION_HTTPS', false);
define('CATEGORIE_PAR_DEFAUT', 'Notes');
define('LOCALISATION_TEMPLATE', '../Moteur/Template/template.php');
define('LOCALISATION_HEADER_TEMPLATE', '../Moteur/Template/header.php');
define('LOCALISATION_FOOTER_TEMPLATE', '../Moteur/Template/footer.php');
define('DOSSIER_ELEMENTS_DESIGN_TEMPLATE', '../Moteur/Template/ElementsAnnexes');
define('TAILLE_MAX_RESSOURCES', 300 * 1024); //en octets
define('REPERTOIRE_BUILD', '../Moteur/Build');
define('REPERTOIRE_DESTINATION_JSON', '../Moteur/Build/DataJson');
define('REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR', '../Moteur/Build/DataJsonErreur');
define('REPERTOIRE_DESTINATION_RENDU_PHP', '../Rendu');
define('REPERTOIRE_CODE_PHP', '../Moteur');
define('REPERTOIRE_BILLETS', '../Contenu/ContenuBillets');
define('REPERTOIRE_PAGES_ERREUR', '../Contenu/PagesErreur');
define('REPERTOIRE_TEMPLATE', '../Moteur/Template');
define('REPERTOIRE_CONTENU_IMAGE', '../Contenu/Images');
define('REPERTOIRE_RENDU_IMAGE', '../Rendu/Images');
define('REPERTOIRE_IMAGE', 'Images');
define('NOM_FICHIER_SITEMAP', 'sitemap.xml');
//Choix de la zone pour l'affichage des heures
define('ZONE_TEMPORELLE_HEURE', 'fr_FR');
//Pour controle théorique fichier markdown
define('NOMBRE_BLOC_FICHIER_MARKDOWN', 2); //Ici le bloc d'en-tête et le bloc de contenu
define('DELIMITEUR_BLOCS_MARKDOWN', '---');
define('NOM_FICHIER_VERIFICATION_TIMESTAMP', '../Moteur/timestamp.php');



