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

    <br>
    <br>
    <div class='main-content'>
        <?php
        $prof_u = NULL;
        $bd = new GestionBD();
        if ($_GET["id"] == $_SESSION["util"]->getId()) {
            $prof_u = $_SESSION["util"];
        } else {
            if (isset($_GET['idSupp'])) {
                if ($bd->isAllowed($_SESSION["util"]->getId(), $_GET['idSupp'])) {
                    $bd->supprimerAmi($_SESSION['util']->getId(), $_GET['idSupp']);
                }
            }
            if (isset($_GET['idAdd'])) {
                if (!$bd->isAllowed($_SESSION["util"]->getId(), $_GET['idAdd'])) {
                    $bd->addAmi($_SESSION['util']->getId(), $_GET['idAdd']);
                }
            }
            $prof_u = $bd->getUtilisateur($_GET["id"]);
        }
        if ($bd->isAllowed($_SESSION["util"]->getId(), $prof_u->getId())) {
            $cave_u = $bd->getCave($_GET['id']); ?>

            <?php
            $prof_u->afficherInfo(true, $_SESSION["util"]->getId());
            ?>

            <div class='rightSide' style='max-width:50%;'>
                <a href=<?php echo "'cave.php?id=" . $prof_u->getId() . "' "; ?> class='fen-apercu rightSide' style='width:90%;'>
                    <h3 id="ut"> <img src='img/logo_alco2.gif' alt='' width='40px' height='40px'> Cave ?? bi??re (<?php echo sizeof($cave_u) ?>) </h3>
                </a>
                <?php $listeamis_u = $bd->getAmis($_GET['id']); ?>
                <!--echo "<h3>" . sizeof($listeamis_u) . " R??sultats </h3>"; -->
                <a href=<?php echo "'listeamis.php?id=" . $prof_u->getId() . "'"; ?> class='fen-apercu rightSide'  style='width:90%;'   >
                    <h3 id="ut"> <img src='img/photoProf.png' alt='' width='40px' height='40px'> Abonnements (<?php echo sizeof($listeamis_u); ?>) </h3>
                </a>
                <?php $listerelations_u = $bd->getRelations($_GET['id']); ?>
                <a href=<?php echo "'listeamis.php?id=" . $prof_u->getId() . "&choix=true'"; ?> class='fen-apercu rightSide'  style='width:90%;'>
                    <h3 id="ut"><img src='img/photoProf.png' alt='' width='40px' height='40px'>  Abonn??s (<?php echo sizeof($listerelations_u); ?>) </h3>
                </a>
            </div>


        <?php
    } else {
        $prof_u->afficherInfo(false, $_SESSION["util"]->getId());
    }
    ?>

    </div>
    <?php include("navbar/navbar.php"); ?>
</body>