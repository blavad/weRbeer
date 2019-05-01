<?php
require_once('class/utilisateur.class.php');
require_once('class/avis.class.php');
require_once('class/biere.class.php');
require_once('class/gestionBD.class.php');
session_start();
?>


<!DOCTYPE html>
<html>

<head>
    <title>Cave</title>
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
        $cave_u = NULL;
        $bd = new GestionBD();
        if ($bd->isAllowed($_SESSION["util"]->getId(), $_GET['id'])) {
            $cave_u = array();
            $cave_u[0] = new Avis(new Biere("Delirium Tremens", "8.5", "img/cdt.png"), 4.3, "Delicieuse et bien amer.");
            for ($i = 0; $i < 10; $i++) {
                $cave_u[0]->afficherAvis();
            }
            ?>
        <?php
    } else {
        $prof_u->afficherInfo(false);
    }
    ?>


        <?php
        include("navbar/navbar.php");
        ?>

    </div>
</body>