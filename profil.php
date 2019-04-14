<?php
require_once('class/utilisateur.class.php');
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
        $prof_u = NULL;
        if ($_GET["id"] == $_SESSION["util"]->getId()) {
            $prof_u = $_SESSION["util"];
        } else {
            $prof_u = new Utilisateur($_GET["id"], "Marion", "img/prof.png");
            // $prof_u = $gbd -> getUtilisateur($_GET["id"]);        
        }

        $prof_u->afficherInfo();
        ?>

        <a href=<?php echo "'bieres.php?id=" . $prof_u->getId() . "' "; ?> class='fen-apercu leftSide'>
            <h3 style=" color: black;
                "> Cave à bière </h3>
        </a>

        <a href=<?php echo "'amis.php?id=" . $prof_u->getId() . "' "; ?> class='fen-apercu rightSide'>
            <h3 style=" color: black;
                "> Relations </h3>
        </a>

    </div>
</body>