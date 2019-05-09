<?php
require_once('class/utilisateur.class.php');
require_once('class/gestionBD.class.php');
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <title>weRbeer -- Amis</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <link rel="stylesheet" type="text/css" href="style.css" media="all" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $("legend.recherche").click(function() {
                if ($("fieldset.recherche").is(":hidden")) {
                    $("fieldset.recherche").show("slow", "swing");
                } else {
                    $("fieldset.recherche").hide("slow", "swing");
                }
            });
        });
    </script>

</head>

<body>
    <?php
    include("header/header.php");
    ?>

    <div class='main-content'>
        <?php
        $listeamis_u = NULL;
        $bd = new GestionBD();
        if (isset($_GET['id'])) {
            if ($bd->isAllowed($_SESSION["util"]->getId(), $_GET['id'])) {
                if (isset($_GET['idSupp'])) {
                    $bd->supprimerAmi($_GET['id'], $_GET['idSupp']);
                }
                $util = $bd->getUtilisateur($_GET['id']);
                echo "<h1> Amis de " . $util->getPseudo() . " </h1>"; ?>

                <!-- Formulaire de recherche avancée  -->
                <form method="get" action="listeamis.php">
                    <legend class="recherche" id='recherche'> <i class="glyphicon glyphicon-search"></i> Recherche avancée</legend>
                    <fieldset class="recherche">
                        <input type="text" style="display:none;" name="id" value="<?php echo $_GET['id']; ?>">
                        <label for="" class="rechercheNom">Recherche par pseudo : </label>
                        <br />
                        <br />
                        <input class="option_recherche" placeholder="Saisissez le pseudo" id="pseudo" name="pseudo" type="text" size="30" value="" />
                        <br />
                        <hr />
                        <input class="button_recherche" type="submit" value="Rechercher" />
                    </fieldset>
                </form>

                <!-- Récupération des données du formulaire traitement associé  -->
                <?php
                $pseudo = "%";
                if (isset($_GET['pseudo']) && $_GET['pseudo'] != "") {
                    $pseudo = $_GET['pseudo'];
                }
                if (isset($_GET['choix'])) {
                    $listeamis_u = $bd->getRelations($_GET['id']);
                } else {
                    $listeamis_u = $bd->getAmis($_GET['id'], $pseudo);
                }
                echo "<h3>" . sizeof($listeamis_u) . " Résultats </h3>";
                for ($i = 0; $i < sizeof($listeamis_u); $i++) {
                    $listeamis_u[$i]->afficherAmis($_SESSION["util"]->getId() == $_GET['id'] && !isset($_GET['choix']), $_SESSION["util"]->getId());
                }
            }
        }
        ?>

    </div>
    <?php include("navbar/navbar.php"); ?>
</body>

</html>