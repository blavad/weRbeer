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


    public function __construct($id, $nom, $prenom, $pseudo, $dateNaissance = "01/01/2000", $sexe = "Unknown", $url_photo = "photo_marion.png")

    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->identifiant = $id;
        $this->pseudo = $pseudo;
        $this->sexe = $sexe;
        $this->dateNaissance = $dateNaissance;
        $this->url_photo = $url_photo;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
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
        return "user/" . $this->pseudo . "/" . $this->url_photo;
    }

    public function afficherInfo($total = true, $myId)
    {
        echo
            "<div class='leftSide' style='max-width:50%'>";
        $this->afficherPhoto(180, 180);
        echo "<h2> " .  htmlspecialchars($this->getPrenom()) . " " . htmlspecialchars($this->getNom()) . "(" . htmlspecialchars($this->getPseudo()) . ")" . "</h2>
                <h3> " . $this->getDateNaissance() . " </h3> <br>";
        if (!($this->getId() == $myId)) {
            if ($total) {
                echo "<a href='profil.php?id=" . $this->getId() . "&idSupp=" . $this->getId() . " 'class='delete_avis leftSide'> Supprimer <i class='glyphicon glyphicon-remove'></i></a>";
            }
            if ($total == false) {
                echo "<a href='profil.php?id=" . $this->getId() . "&idAdd=" . $this->getId() . " 'class='add_avis leftSide'> Ajouter <i class='glyphicon glyphicon-plus'></i></a>";
            }
        }
        echo "</div>";
    }

    public function afficherAmis($supp = false, $myId=NULL)
    {
        echo
            "<article class='blocApercu'>
            <div class='blocImage'>";
        $this->afficherPhoto(50, 50);
        echo "
            </div>";
        echo "
            <div class='blocDescription'>
            <a href='profil.php?id=" . $this->getId() . "' id='descriptionTitle'> " . htmlspecialchars($this->getPrenom()) .  " " . htmlspecialchars($this->getNom()) .  " -- " . $this->getPseudo() .  "</a>";
        if ($supp) {
            echo "<a href='listeamis.php?id=" . $myId . "&idSupp=" . $this->getId() . "' class='delete_avis'><i class='glyphicon glyphicon-remove'></i></a>";
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
