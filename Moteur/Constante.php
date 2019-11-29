<?php


class Constante
{
    const MODE_DEBUG = false;
    const REDIRECTION_HTTPS = false;
    const LOCALISATION_TEMPLATE='../Moteur/Template/template.php';
    const LOCALISATION_HEADER_TEMPLATE = '../Moteur/Template/header.php';
    const LOCALISATION_FOOTER_TEMPLATE = '../Moteur/Template/footer.php';
    const DOSSIER_ELEMENTS_DESIGN_TEMPLATE ='../Moteur/Template/ElementsAnnexes';
    const TAILLE_MAX_RESSOURCES = 300*1024; //en octets
    const REPERTOIRE_BUILD = "../Moteur/Build";
    const REPERTOIRE_DESTINATION_JSON = "../Moteur/Build/DataJson";
    const REPERTOIRE_DESTINATION_JSON_PAGE_ERREUR = "../Moteur/Build/DataJsonErreur";
    const REPERTOIRE_DESTINATION_RENDU_PHP = "../Rendu";
    const REPERTOIRE_CODE_PHP = "../Moteur";
    const REPERTOIRE_BILLETS = "../Contenu/ContenuBillets";
    const REPERTOIRE_PAGES_ERREUR = "../Contenu/PagesErreur";
    const REPERTOIRE_TEMPLATE = "../Moteur/Template";
    const REPERTOIRE_CONTENU_IMAGE  = "../Contenu/Images";
    const REPERTOIRE_RENDU_IMAGE  = "../Rendu/Images";
    const REPERTOIRE_IMAGE = "Images";
    const NOM_FICHIER_SITEMAP = "sitemap.xml";
    const MOT_PAR_MINUTE = 260;
    //Gestion des titres
    const NOM_DU_SITE = "Ecto";
    const NOM_PAGE_ACCUEIL = "Accueil";
    const DESCRIPTION_PAGE_ACCUEIL = "Mini-CMS, 0 dépendance, - de 100 ko, Markdown, Déploiement Copier/coller";
    //Gestion du sitemap
    const ADRESSE_EXACTE_SITE="http://localhost/Ecto/Rendu";
    // La constante EMOJI_LONGUEUR_LECTURE est utilisée pour savoir si le temps de lecture sera accompagné d'emoji
    const EMOJI_LONGUEUR_LECTURE = true;
    const EMOJI_5_MINUTES = '☕️';
    const EMOJI_10_MINUTES = '🍻';
    //Choix de la zone pour l'affichage des heures
    const ZONE_TEMPORELLE_HEURE = "fr_FR";
    //Pour controle théorique fichier markdown
    const NOMBRE_BLOC_FICHIER_MARKDOWN = 2; //Ici le bloc d'en-tête et le bloc de contenu
    const DELIMITEUR_BLOCS_MARKDOWN = "===";
    const NOM_FICHIER_VERIFICATION_TIMESTAMP = "../Moteur/timestamp.php";
    const NOMBRE_DE_SECONDES_AVANT_MISE_A_JOUR= 0;
}

