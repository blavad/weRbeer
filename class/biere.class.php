<?php

class Biere
{
    protected $id;
    protected $nom;
    protected $degre;
    protected $fabriquant;
    protected $fabrication;
    protected $appelation;
    protected $ingredients;
    protected $moyenne;
    protected $nbAvis;
    protected $url_photo;

    public function __construct($n, $d, $url)
    {
        $this->nom = $n;
        $this->degre = $d;
        $this->url_photo = $url;
        $this->nbAvis = 0;
        $this->moyenne = 0;
        $this->ingredients = NULL;
        $this->fabriquant = NULL;
        $this->fabrication = NULL;
        $this->appelation = NULL;
        
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
    
    public function getUrl_photo()
    {
        return $this->url_photo;
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
}
