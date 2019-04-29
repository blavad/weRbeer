<?php

class Utilisateur
{
    protected $identifiant;
    protected $pseudo;
    protected $dateNaissance;
    protected $sexe;
    protected $url_photo;

    public function __construct($id, $pseudo, $dateNaissance, $sexe, $url_photo)
    {
        $this->identifiant = $id;
        $this->pseudo = $pseudo;
        $this->sexe = $sexe;
        $this->dateNaissance = $dateNaissance;
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
        return "photoU/".$this->url_photo;
    }

    public function afficherInfo()
    {
        echo
            "<div >
                <div >" . $this->afficherPhoto(200, 200) . "</div>
                <h2> " . $this->getPseudo() . "</h2>
                <h2> ".$this->getId()."</h2>
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
                <img src='" . $this->getURL_Photo() . "'  alt='".$this->getURL_Photo()."' width='" . $width . "px' height='" . $height . "px'>
            </a>";
    }
}
