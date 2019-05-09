<?php
require_once('class/utilisateur.class.php');
require_once('class/biere.class.php');
require_once('class/avis.class.php');
require_once('class/gestionBD.class.php');
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
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
    <?php
    function afficherActuBlock($bd, $quoi, $type = "ami")
    {
        echo "<div class='actu_block'>";
        if ($type == "ami") {
            echo "<h3 class='titre_actu'> <a href='profil.php?id=" . $quoi->getId() . "' style='font-weight:bold;'>" . $quoi->getPseudo() . "</a> a commencé à vous suivre.</h3>";
            $quoi->afficherAmis();
        } else {
            $util = $bd->getUtilisateur($quoi->getIdU());
            echo "<h3 class='titre_actu'> <a href='profil.php?id=" . $quoi->getIdU() . "' style='font-weight:bold;'>" . $util->getPseudo() . "</a> a ajouté un avis.</h3>";
            $quoi->afficherAvis();
        }
        echo "</div>";
    }
    ?>

</head>

<?php
include("header/header.php");
?>


<div class='main-content'>
    <?php
    $l_actus = NULL;
    $bd = new GestionBD();
    if (isset($_SESSION['util'])) {

        $nb_actus_init = 5;
        $nb_max_actus = $nb_actus_init;
        if (isset($_GET["nb_actus"])) {
            $nb_max_actus = $_GET["nb_actus"];
        }

        echo "<h1> Fil d'actualités </h1><br>";

        $l_actus = $bd->getActualites($_SESSION['util']->getId(), $nb_max_actus);
        $i = 0;

        $actus_display = array();
        foreach ($l_actus as $type_actu => $actu) {
            $i = 0;
            while ($i < $nb_max_actus && $i < sizeof($actu)) {
                $actus_display[] = array('type' => $type_actu, 'actu' => $actu[$i]);
                $i++;
            }
        }
        shuffle($actus_display);
        foreach ($actus_display as $actu_d) {
            afficherActuBlock($bd, $actu_d['actu'], $actu_d['type']);
        }
        echo "<form method='get' action='actu.php'>
        <input type='number' style='display:none;' name='nb_actus' value='" . ($nb_max_actus + $nb_actus_init*2) . "' />
        <input class='button_recherche' type='submit' value='Plus d actualités' />
        </form>";
    }
    ?>


</div>

<?php include("navbar/navbar.php"); ?>
</body>

</html>