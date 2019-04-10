<?php

class GestionBD
{
    static protected $bd;

    public function __construct(){
        try {
            $this->bd = new PDO('mySQL:host=localhost;dbname=weRbeer;charset=utf8', 'david','divad1997');
        } catch (Exception $err){
            die('Erreur'.$err->getMessage());
        }
    }

    function connexion($pseudo, $mdp)
    {
        return TRUE;
    }

    function getUtilisateur($pseudo)
    { }

    function getAmis($pseudo)
    { }

    function getCave($pseudo)
    { }

    function getBiere($nomBiere)
    { }

    function getActualites($pseudo)
    { }

    function addAmi($mon_pseudo, $pseudo_ami)
    { }

    function supprimerAmi($mon_pseudo, $pseudo_ami)
    { }

    function addAvis($mon_pseudo, $com, $note)
    { }

    function supprimerAvis($mon_pseudo, $nomBiere)
    { }
}

?>