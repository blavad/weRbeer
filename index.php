<?php
require_once('class/utilisateur.class.php');
session_start();
require_once('class/gestionBD.class.php');

if (isset($_GET['deconnect'])) {
  $_SESSION['util'] = NULL;
}
if (isset($_SESSION['util'])) {
  include("actu.php");
} else {
  if (!isset($_POST['identifiant']) | !isset($_POST['mdp'])) {
    include("connexion.html");
  } else {
    $bd = new GestionBD(); 
    if (isset($_POST['name']) && isset($_POST['surname'])) {
      // Inscription utilisateur 
      $inscr = $bd->inscrire($_POST, $_FILES);
      echo $inscr['errMessage'];
      if (!$inscr['erreur']) {
        $connect_info = $bd->connexion($_POST["identifiant"], $_POST["mdp"]);
        if ($connect_info[1]) {
          $_SESSION['util'] = $bd->getUtilisateur($connect_info[0]);
          include("actu.php");
        } else {
          include("connexion.html");
          echo "<div class='max-width' style='position: fixed; background:red; color : white; bottom:0; text-align:center;'> Erreur d'inscription " . $inscr['errMessage'] . " </div>";
        }
      }
    } else {
      // Simple Connexion
      $connect_info = $bd->connexion($_POST["identifiant"], $_POST["mdp"]);
      if ($connect_info[1]) {
        $_SESSION['util'] = $bd->getUtilisateur($connect_info[0]);
        include("actu.php");
      } else {
        include("connexion.html");
        echo "<div class='max-width' style='position: fixed; background:red; color : white; bottom:0; text-align:center;'> Erreur lors de la connexion </div>";
      }
    }
  }
}
