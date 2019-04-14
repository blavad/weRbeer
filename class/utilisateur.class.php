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

    public function getId()
    {
        return $this->identifiant;
    }
    public function getURL_Photo()
    {
        return $this->url_photo;
    }

    public function afficherInfo()
    {
        echo
            "<div >
                <div >" . $this->afficherPhoto(200, 200) . "</div>
                <h2> " . $this->getPseudo() . "</h2>
            </div>";
    }

    public function afficherListeAmis()
    {
        echo "<div>
        <h2> Amis </h2>
        </div>
        ";
    }

    public function afficherCave()
    {
        echo "<div>
        <h2> Cave</h2>
        </div>
        ";
    }

    public function afficherPhoto($width, $height)
    {
        echo
            "<a href ='profil.php?id=" . $this->getId() . "'>
                <img src='" . $this->getURL_Photo() . "'  alt='Photo Profile' width='" . $width . "px' height='" . $height . "px'>
            </a>";
    }
}
