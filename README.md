# Ecto

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=Laleloulilo_Ecto&metric=alert_status)](https://sonarcloud.io/dashboard?id=Laleloulilo_Ecto)

Mini CMS optimisé pour le blogging

- Moins de 100 ko
- Moins de 100 ms de temps de génération
- Base de donnée de fichier
- Pas de dépendance à des services/librairies externes

## Pour commencer

### Pré-requis

- Php installé (version codée sous Php 7.3)
- Extension tidy activée

Pour le premier Test, vous pouvez changer l'adresse de votre site ainsi que différents éléments dans _..Php/Constante.php_ :
- Répertoire des Billets _(par défaut ../Contenu/ContenuBillets)_ ;
- Répertoire des Pages d'erreurs _(par défaut ../Contenu/PagesErreur)_ ;
- Répertoire des images _(par défaut ../Contenu/Images)_ ;
- L'adresse du site _(ADRESSE_EXACTE_SITE dans ..Php/Constante.php)_.

### Lancement

Lancez le build en allant à l'adresse ...Ecto/Rendu/_ puis rechargez la page pour consulter le build.

## Formatage des articles

Vous pouvez rajouter des articles ou modifier des pages d'erreur selon le template suivant. Si il n'est pas suivi elles ne seront pas interprétées

```YAML
===
titre: le titre de votre article
date: la date que vous souhaitez voir associée à votre article (ex : 3/5/2015)
description: description succincte de votre article 
===
Corps de votre article
```

## Rendu final

Une page d'accueil sera produite contenant un lien vers chacun de vos articles par ordre anté-chronologique.
Chacun mène à l'article qui lui est associé.
