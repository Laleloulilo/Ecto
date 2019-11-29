<?php

// Classe générique de contenu Markdown
class Article
{
  public $enTete;
  public $contenu;

  public function __construct($enTete, $contenu)
  {
    $this->enTete = $enTete;
    $this->contenu = $contenu;
  }

  public function getEnTete()
  {
    return $this->enTete;
  }

  public function setEnTete($enTete)
  {
    $this->enTete = $enTete;
  }

  public function getContenu()
  {
    return $this->contenu;
  }

  public function setContenu($contenu)
  {
    $this->contenu = $contenu;
  }
}
