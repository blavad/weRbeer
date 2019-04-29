<?php
require_once('class/utilisateur.class.php');
session_start();
require_once('class/gestionBD.class.php');

if (isset($_GET['deconnect'])){
  $_SESSION['util']=NULL;
}
if (isset($_SESSION['util'])) { 
  include("actu.php");
} else {
  if (!isset($_POST['identifiant']) | !isset($_POST['mdp'])) {
    include("connexion.html");
  } else {
    
    $bd = new GestionBD();
    $connect_info = $bd->connexion($_POST["identifiant"], $_POST["mdp"]);
    if ($connect_info[1]) {
      $_SESSION['util'] = $bd->getUtilisateur($connect_info[0]);
      include("actu.php");
    } else {
      include("connexion.html");
    }
  }
}
