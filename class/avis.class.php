<?php

class Avis
{
    protected $biere;
    protected $note;
    protected $commentaire;
    protected $date;

    public function __construct($b, $n, $com = "", $d = "01/01/2000")
    {
        $this->biere = $b;
        $this->note = $n;
        $this->commentaire = $com;
        $this->date = $d;
    }

    public function getBiere()
    {
        return $this->biere;
    }

    public function getNote()
    {
        return $this->note;
    }
    public function getPseudo()
    {
        return $this->pseudo;
    }

    public function getId()
    {
        return $this->identifiant;
    }

    public function getCommentaire()
    {
        return $this->commentaire;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function afficherAvis()
    {
        echo 
        "<article class='blocApercu'>
        <div class='blocImage'>";
        $this->getBiere()->afficherPhoto(40, 80);
        echo "
        </div>
        <div class='blocDescription'>
        <a href='bieres.php?id=" . $this->getBiere()->getNom() . "'> <span id='descriptionTitle'>" . $this->getBiere()->getNom() .  "</span></a>";
        echo " (<span style='color:red; font : bold;'>".$this->getNote(). "</span>/5)";
        echo "<div id='blocCommentaire'>".$this->getCommentaire(). " 
        </div> 
        </div>
        </article>";
    }
}
