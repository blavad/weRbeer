<?php

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

    public function __construct($nomB, $type, $mf, $deg, $moy, $nomMar, $url)
    {
        $this->nom = $nomB;
        $this->degre = $deg;
        $this->appelation = $type;
        $this->fabrication = $mf;
        $this->moyenne = $moy;
        $this->fabriquant = $nomMar;
        $this->url_photo = $url;
        $this->nbAvis = 0;
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

    public function afficherInfo()
    {
        echo
            "<div id='blockBiere'>
				<div id='photoBiere'> <img src='" . $this->getUrl_photo() . " '  alt='Photo Profile' width='100px' height='400px'> </div>
				<div id='titreBiere'> " . $this->getNom() . "</div>
				<div id='infoBiere'>
					Alcoolémie : " . $this->getDegre() . "% <br>
					Note moyenne : " . $this->getMoyenne() . "/20 (" . $this->getNbAvis() . " avis) <br>
					
				</div>
            </div>";
    }

    public function noter()
    {
        echo
            "<form>
				<div id='blockNote' class='centerPart' font-weight='bold'>
					<div style='margin-left:70px'><b>Note : <b/> <input style='width:80px'type='number' min='0' max='5' step='0.5' value='5' name='note'/> </div><br>
					<div><textarea style='margin-left:50px' name='com'></textarea> </div><br>
					<input style='margin-left:110px' type='submit' value='Ajouter'/>
				</div>
			</form>";
    }

    public function afficherPhoto($width, $height)
    {
        echo
            "<img src='" . $this->getUrl_photo() . "' width='" . $width . "px' height='" . $height . "px'>";
    }

    public function afficherBiere()
    {
        echo
            "<article class='blocApercu'>
        <div class='blocImage'>";
        $this->afficherPhoto(35, 120);
        echo "
        </div>";
        echo "
        <div class='blocDescription'>
        <a href='bieres.php?id=" . $this->getNom() . "' id='descriptionTitle'> " . $this->getNom() .  "</a>";
        echo "<div id='logo_alco'><img src='http://www.gifsanimes.com/data/media/331/biere-image-animee-0035.gif' border='0' alt='' />
        <h2 id='text_alco'>" . $this->getDegre() . " % Vol</h2>
        </div>";
        echo " <span style='color:red; font : bold; font-size:14px;margin-left : 40px;'> <br>" . $this->getMoyenne() . "</span>/5 (" . $this->getNbAvis() . " avis) ";
        echo "<div id='buttonCommentaire' style='margin-top:5px;'> Appelation : " . $this->getType() . " <br> Mode de Fabrication : " . $this->getMF() . " <br> Marque : " . $this->getMarque() . " </div> 
        </div>
        </article>";
    }
}
