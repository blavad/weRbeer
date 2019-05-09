<?php
require_once('class/gestionBD.class.php');

class Biere
{
    protected $nom;
    protected $degre;
    protected $fabriquant;
    protected $fabrication;
    protected $appelation;
    protected $ingredients;
    protected $moyenne;
    protected $nbAvis;
    protected $url_photo;

    public function __construct($nomB, $type, $mf, $deg, $moy, $nomMar, $url, $nbAv = 0)
    {
        $this->nom = $nomB;
        $this->degre = $deg;
        $this->appelation = $type;
        $this->fabrication = $mf;
        $this->moyenne = $moy;
        $this->fabriquant = $nomMar;
        $this->url_photo = $url;
        $this->nbAvis = $nbAv;
        $this->ingredients = NULL;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getDegre()
    {
        return $this->degre;
    }

    public function getNbAvis()
    {
        return $this->nbAvis;
    }

    public function getMoyenne()
    {
        return $this->moyenne;
    }

    public function getMarque()
    {
        return $this->fabriquant;
    }

    public function getType()
    {
        return $this->appelation;
    }

    public function getMF()
    {
        return $this->fabrication;
    }

    public function getUrl_photo()
    {
        return "photoB/" . $this->url_photo;
    }

    public function setNbAvis($nbA){
        $this->nbAvis = $nbA;
    }

    public function afficherInfo()
    {
        echo
            "<div id='blockBiere'>
				<div id='photoBiere'> <img src='" . $this->getUrl_photo() . " '  alt='Photo Profile' width='100px' height='400px'> </div>
				<div id='titreBiere'> " . $this->getNom() . "</div>
				<div id='infoBiere'>
					Alcoolémie : " . $this->getDegre() . "% <br>
					Note moyenne : " . $this->getMoyenne() . "/20 (" . $this->getNbAvis() . " avis) <br>
					Marque : " . $this->getMarque() . " <br/>
					Fabrication : " . $this->getMF() . " <br/>
					Appelation : " . $this->getType() . " <br/>
				</div>
            </div>";
    }

    public function noter()
    {
        echo
            "<form method='POST' action='biere.php?nomB=".$this->getNom()."'>
				<fieldset id='blockNote' class='centerPart'>
					<input style='width:36%;margin-left:32%' type='number' min='0' max='5' step='0.1' value='5' name='note'/><br />
					<textarea style='width:90%;margin-left:5%' name='com'></textarea><br/>
					<input style='width:50%;margin-left:25%' type='submit' value='Ajouter'/>
				</fieldset>
			</form>";
    }

    public function afficherPhoto($width, $height)
    {
        echo
            "<img src='" . $this->getUrl_photo() . "' width='" . $width . "px' height='" . $height . "px'>";
    }

    public function initAffichageAuClic($idBalise = "", $msg = "Aucun message")
    {
        echo  "<script>
            $(document).ready(function () {
                $('." . $idBalise . "').click(function () {
                    
                });
            });
        </script>";
    }

    public function afficherBiere()
    {
        $bd = new GestionBD();
        $this->initAffichageAuClic($this->getMF(), htmlspecialchars($bd->getMFDescription($this->getMF())['description']));
        echo
            "<article class='blocApercu'>
        <div class='blocImage'>";
        $this->afficherPhoto(35, 120);
        echo "
        </div>";
        echo "
        <div class='blocDescription'>
        <a href='biere.php?nomB=" . $this->getNom() . "' id='descriptionTitle'> " . htmlspecialchars($this->getNom()) .  "</a>";
        echo "<div id='logo_alco'><img src='http://www.gifsanimes.com/data/media/331/biere-image-animee-0035.gif' border='0' alt='' />
        <h2 id='text_alco'>" . htmlspecialchars($this->getDegre()) . " % Vol</h2>
        </div>";
        echo " <span style='color:red; font : bold; font-size:14px;margin-left : 40px;'> <br>" . htmlspecialchars($this->getMoyenne()) . "</span>/5 (" . $this->getNbAvis() . " avis) ";
        echo "<div class='buttonCommentaire' style='margin-top:5px;'> 
        Appelation : " . htmlspecialchars($this->getType()) . " 
        <br> 
        Mode de Fabrication : <span class='" . $this->getMF() . "' style='cursor:pointer;' title='" . htmlspecialchars($bd->getMFDescription($this->getMF())['description']) . "'>" . htmlspecialchars($this->getMF()) . "</span> 
        
        <br> ";
        $mar = $bd->getMarqueInfos($this->getMarque());
        echo "<span style='cursor:pointer;' title='Créée en " . htmlspecialchars($mar['annee']) . " à " . htmlspecialchars($mar['lieu']) . "'>Marque : " . $this->getMarque() . "</span> </div> 
        </div>
        </article>";
    }
}
