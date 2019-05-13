<?php



class Avis
{
    protected $idU;
    protected $biere;
    protected $note;
    protected $commentaire;
    protected $date;

    public function __construct($id, $b, $n, $com = "", $d = "01/01/2000")
    {
        $this->idU = $id;
        $this->biere = $b;
        $this->note = $n;
        $this->commentaire = $com;
        $this->date = $d;
    }

    public function getIdU()
    {
        return $this->idU;
    }

    public function getBiere()
    {
        return $this->biere;
    }

    public function getNote()
    {
        return $this->note;
    }

    public function getCommentaire()
    {
        return $this->commentaire;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function afficherAvis($supp = false)
    {
        echo
        "<article class='blocApercu'>
        <div class='blocImage'>";
        $this->getBiere()->afficherPhoto(35, 120);
        echo "
        </div>";
                if ($supp) {      
                    echo "<a href='cave.php?id=".$this->getIdU()."&nomBSupp=".$this->getBiere()->getNom()."' class='delete_avis'><i class='glyphicon glyphicon-remove'></i></a>";
                }
        echo "
        <div class='blocDescription'>
        <a href='biere.php?nomB=" . $this->getBiere()->getNom() . "' id='descriptionTitle'> " . $this->getBiere()->getNom() .  "</a>";
        echo " (<span style='color:red; font : bold;'>" . $this->getNote() . "</span>/5)";
        echo "<div id='buttonCommentaire'> Commentaire... <div id='blocCommentaire'>" . $this->getCommentaire() . " </div></div> 
        </div>
        </article>";
    }
}
