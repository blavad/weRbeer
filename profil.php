<?php
require_once('class/utilisateur.class.php');
require_once('class/gestionBD.class.php');
session_start();
?>


<!DOCTYPE html>
<html>

<head>
    <title>weRbeer -- Profil</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css" media="all" />

</head>

<body>

    <?php
    include("header/header.php");
    ?>

    <div class='main-content'>
        <?php
        $prof_u = NULL;
        $bd = new GestionBD();
        if ($_GET["id"] == $_SESSION["util"]->getId()) {
            $prof_u = $_SESSION["util"];
        } else {
            $prof_u = $bd->getUtilisateur($_GET["id"]);
        }
        if ($bd->isAllowed($_SESSION["util"]->getId(), $prof_u->getId())) {
            $prof_u->afficherInfo(true); ?>
            <a href=<?php echo "'cave.php?id=" . $prof_u->getId() . "' "; ?> class='fen-apercu leftSide'>
                <h3 style="color: black;"> Cave à bière </h3>
            </a>

            <a href=<?php echo "'amis.php?id=" . $prof_u->getId() . "' "; ?> class='fen-apercu rightSide'>
                <h3 style="color: black;"> Liste d'amis </h3>
            </a>
        <?php
    } else {
        $prof_u->afficherInfo(false);
    }
    ?>

    </div>
    <?php include("navbar/navbar.php");?>
</body>