<?php
require_once('class/utilisateur.class.php');
require_once('class/gestionBD.class.php');
?>

<!DOCTYPE html>
<html>

<head>
    <title>weRbeer -- Actus</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <link rel="stylesheet" type="text/css" href="style.css" media="all" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>

<?php
include("header/header.php");
?>

<div class='main-content'>
    <?php
    $listeamis_u = NULL;
    $bd = new GestionBD();
    if (isset($_SESSION['util'])) {

        $nb_max_actus = 20;
        if (isset($_POST["nb_actus"])){
            $nb_max_actus = $_POST["nb_actus"];
        }

        $actus = $bd->getActualites($_SESSION['util']->getId(), $nb_max_actus);
        
        echo "<h1> Fil d'actualités </h1>"; 
        for ($i = 0; $i < sizeof($actus); $i++) {
            //$listeamis_u[$i]->afficherAmis($_SESSION["util"]->getId() == $_GET['id'], $_SESSION["util"]->getId());
        }
    }
    ?>

</div>

<?php include("navbar/navbar.php"); ?>
</body>

</html>