<?php

class Utilisateur
{
    protected $identifiant;
    protected $pseudo;
    protected $dateNaissance;
    protected $sexe;
    protected $url_photo;
    protected $nom;
    protected $prenom;

    
    public function __construct($id, $pseudo, $dateNaissance="01/01/2000", $sexe="Unknown", $url_photo="photo_marion.png")
    {
        $this->identifiant = $id;
        $this->pseudo = $pseudo;
        $this->sexe = $sexe;
        $this->dateNaissance = $dateNaissance;
        $this->url_photo = $url_photo;

    }

    public function getNom()
    {
        return "Schaeff";
    }

    public function getPrenom()
    {
        return "Mar";
    }

    public function getPseudo()
    {
        return $this->pseudo;
    }

    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    public function getId()
    {
        return $this->identifiant;
    }
    
    public function getURL_Photo()
    {
        return "user/".$this->pseudo."/".$this->url_photo;
    }

    public function afficherInfo($total = true)
    {
        echo
            "<div >
                <div >" . $this->afficherPhoto(150, 150) . "</div>
                <h2> " . $this -> getNom() . $this->getPrenom() . "(" . $this->getPseudo() . ")" . "</h2>
                <h3> " . $this->getDateNaissance() . " </h3>
            </div>";
    }

    public function afficherAmis($supp = false, $myId)
    {
        echo
            "<article class='blocApercu'>
            <div class='blocImage'>";
            $this->afficherPhoto(50, 50);
        echo "
            </div>";
        echo "
            <div class='blocDescription'>
            <a href='profil.php?id=" . $this->getId() . "' id='descriptionTitle'> " . $this->getPseudo() .  "</a>";
            if ($supp) {      
                echo "<a href='listeamis.php?id=" . $myId . "&idSupp=" .$this->getId() . "' class='delete_avis'><i class='glyphicon glyphicon-remove'></i></a>";
            }
        echo " 
            </div>
            </article>";
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
                <img src='" . $this->getURL_Photo() . "'  alt='' width='" . $width . "px' height='" . $height . "px'>
            </a>";
    }
}
