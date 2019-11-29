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

  /**
   * EnTete constructor.
   * @param $date
   * @param $titre
   * @param $description
   * @param $url
   * @param $nbMots
   * @param $timestamp_date
   */
  public function __construct($date, $titre, $description, $url, $nbMots, $timestamp_date)
  {
    $this->date = $date;
    $this->titre = $titre;
    $this->description = $description;
    $this->url = $url;
    $this->nbMots = $nbMots;
    $this->timestamp_date = $timestamp_date;
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

}
