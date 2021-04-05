<?php

// Classe générique de contenu Markdown
class EnTete
{
    public $date;
    public $titre;
    public $description;
    public $url;
    public $nbMots;
    public $timestamp_date;
    public $categorie;
    public $formatArticle;
    public $formatPage;

    public function __construct($titre, $description, $url, $nbMots, $timestamp_date, $categorie, $formatPage)
    {
        if($formatPage){
            //Si c'est l'en-tête d'une page le temps est remplit automatiquement à la date du jour de compilation
            $this->timestamp_date = time();
        }else{
            $this->timestamp_date = $timestamp_date;
        }
        $this->date = ucwords(strftime(FORMAT_DATE, $timestamp_date));
        $this->titre = $titre;
        $this->description = $description;
        $this->url = $url;
        $this->nbMots = $nbMots;
        $this->categorie = $categorie;
        $this->formatPage = $formatPage;
        $this->formatArticle = !$formatPage;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getNbMots()
    {
        return $this->nbMots;
    }

    public function setNbMots($nbMots)
    {
        $this->nbMots = $nbMots;
    }

    public function getTimestampDate()
    {
        return $this->timestamp_date;
    }

    public function setTimestampDate($timestamp_date)
    {
        $this->timestamp_date = $timestamp_date;
    }

    public function getCategorie()
    {
        return $this->categorie;
    }

    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }

    public function getFormatArticle()
    {
        return $this->formatArticle;
    }

    public function setFormatArticle($formatArticle)
    {
        $this->formatArticle = $formatArticle;
    }

    public function getFormatPage()
    {
        return $this->formatPage;
    }

    public function setFormatPage($formatPage)
    {
        $this->formatPage = $formatPage;
    }

}
