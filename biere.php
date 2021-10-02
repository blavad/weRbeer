<?php
require_once('class/utilisateur.class.php');
require_once('class/biere.class.php');
require_once('class/gestionBD.class.php');
session_start();
?>


<!DOCTYPE html>
<html>

<head>
    <title>Profil</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <link rel="stylesheet" type="text/css" href="style.css" media="all" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>

<body>

    <?php
    include("header/header.php");
    ?>

    <div class='main-content'>
        <?php
        if (isset($_SESSION['util'])) {
            $bd = new GestionBD();

            if (isset($_POST['note'])) {
               $bd->addAvis($_SESSION['util']->getID(), $_GET['nomb'], $_POST['note'], $_POST['com']);
            }
            $CDT = $bd->getBiere($_GET['nomb']);
            $CDT->afficherInfo();

            if (!($bd->dejaAjouter($_GET['nomb'], $_SESSION['util']->getID()))) {
                $CDT->noter();
			}
        }
        ?>
    </div>
    <?php
    include("navbar/navbar.php");
    ?>
</body>
