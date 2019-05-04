<?php
require_once('class/utilisateur.class.php');
require_once('class/gestionBD.class.php');
session_start();
$_SESSION["util"] = new Utilisateur(1,'marmar', '30/03/1770','femme');
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
        $ami=true;
        $prof_u = NULL;
        //$bd = new GestionBD();
        if ($_GET["id"] == $_SESSION["util"]->getId()) {
            $prof_u = $_SESSION["util"];
        } else {
            if (isset($_GET['idSupp'])) {
                $ami=false; //$bd->supprimerAmi($_GET['id'], $_GET['idSupp']);
            }
            if (isset($_GET['idAdd'])) {
                $ami=true; //$bd->addAmi($_GET['id'], $_GET['idAdd']);
            }
            $prof_u = new Utilisateur(2,'davdav', '31/03/1770','homme');//$bd->getUtilisateur($_GET["id"]);
        }
        if ($ami){//$bd->isAllowed($_SESSION["util"]->getId(), $prof_u->getId())) {
            $prof_u->afficherInfo(true); ?>
            <a href=<?php echo "'cave.php?id=" . $prof_u->getId() . "' "; ?> class='fen-apercu leftSide'>
                <h3 id="ut"> <img src='img/logo_alco2.gif'  alt='' width='40px' height='40px'> Cave à bière </h3>
            </a>

            <a href=<?php echo "'amis.php?id=" . $prof_u->getId() . "' "; ?> class='fen-apercu rightSide'>
                <h3 id="ut"> <img src='img/photoProf.png'  alt='' width='40px' height='40px'> Liste d'amis </h3>
            </a>
        <?php
    } else {
        $prof_u->afficherInfo(false);
    }
    ?>

    </div>
    <?php include("navbar/navbar.php");?>
</body>