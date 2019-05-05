<?php
require_once('class/utilisateur.class.php');
require_once('class/gestionBD.class.php');
session_start();

$bd = new GestionBD();


function messageResultatAjout($msg, $echec = true)
{
  $color = ($echec) ? "red" : "green";
  return "<div class='max-width' style='position: fixed; background:" . $color . "; color : white; bottom:0; text-align:center;'> 
            Résultat ajout : " . $msg . " </div>";
}

// Gère la déconnexion
if (isset($_GET['deconnect'])) {
  $_SESSION['util'] = NULL;
  $_SESSION['admin'] = NULL;
}
// Si session lancée, on affiche la page d'actus
if (isset($_SESSION['util'])) {

  if (isset($_SESSION['admin'])) {
    $msg = "";
    if (isset($_POST['nomB'])) {
      $ajoutB = $bd->addBiere($_POST, $_FILES);
      $msg = messageResultatAjout($ajoutB['errMessage'], $ajoutB['erreur']);
    }
    if (isset($_POST['nomMar'])) {
      $ajoutMar = $bd->addMarque($_POST);
      $msg = messageResultatAjout($ajoutMar['errMessage'], $ajoutMar['erreur']);
    }
    if (isset($_POST['ville'])) {
      $ajoutLieu = $bd->addLieu($_POST);
      $msg = messageResultatAjout($ajoutLieu['errMessage'], $ajoutLieu['erreur']);
    }
    include('ajoutBiere.php');
    echo $msg;
  } else {
    include("actu.php");
  }
} else {
  if (!isset($_POST['identifiant']) | !isset($_POST['mdp'])) {
    include("connexion.html");
  } else {
    if (isset($_POST['name']) && isset($_POST['surname'])) {
      // Inscription utilisateur 
      echo 'par la';
      $inscr = $bd->inscrire($_POST, $_FILES);
      if (!$inscr['erreur']) {
        $connect_info = $bd->connexion($_POST["identifiant"], $_POST["mdp"]);
        if ($connect_info[1]) {
          $_SESSION['util'] = $bd->getUtilisateur($connect_info[0]);
          include("actu.php");
        }
      } else {
        include("connexion.html");
        echo "<div class='max-width' style='position: fixed; background:red; color : white; bottom:0; text-align:center;'> Erreur d'inscription " . $inscr['errMessage'] . " </div>";
      }
    } else {
      // Connexion administrateur
      if (($_POST["identifiant"] == "admin") && ($_POST["mdp"] == "frosties")) {
        $_SESSION['admin'] = true;
        $_SESSION['util'] = new Utilisateur(0, "", "", 'admin');
        include('ajoutBiere.php');
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
}
