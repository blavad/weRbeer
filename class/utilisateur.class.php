<?php

class Utilisateur
{
    protected $identifiant;
    protected $pseudo;
    protected $url_photo;

    public function __construct($id, $pseudo, $url_photo)
    {
        $this->identifiant = $id;
        $this->pseudo = $pseudo;
        $this->url_photo = $url_photo;
    }

    public function getPseudo()
    {
        return $this->pseudo;
    }

    public function getIdentifiant()
    {
        return $this->identifiant;
    }
    public function getURL_Photo()
    {
        return $this->url_photo;
    }

    public function afficherInfo()
    { }

    public function afficherListeAmis()
    { }

    public function afficherCave()
    { }

    public function afficherPhoto($width, $height)
    {
        echo    
            "<a href ='" . $this->getURL_Photo() . "'>
                <img src='" . $this->getURL_Photo() . "'  alt='Photo Profile' width='" . $width . "px' height='" . $height . "px'>
            </a>";
    }
}
