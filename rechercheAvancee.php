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
    <title>weRbeer -- Recherche</title>
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
        $cave_u = NULL;
        $bd = new GestionBD();
        echo "<h1> Recherche </h1>"; ?>

        <!-- Formulaire de recherche avancée global -->
        <form method="get" action="rechercheAvancee.php">
            <legend class="recherche" id='recherche'> <i class="glyphicon glyphicon-search"></i> Recherche avancée</legend>
            <fieldset class="recherche">
                <input type="text" style="display:none;" name="id" value="<?php echo $_GET['id']; ?>">
                <label for="tri" class="rechercheBiere">Trier par : </label>
                <br />
                <br />
                <select class='option_recherche' name="tri">
                    <option value=""> -- Choisissez une option --</option>
                    <option value="b.nomb">Ordre alphabétique</option>
                    <option value="b.noteMoyenne DESC">Les meilleures</option>
                    <option value="b.noteMoyenne">Les pires</option>
                    <option value="b.alcoolemie DESC">Les plus alcoolisées</option>
                    <option value="b.alcoolemie">Les moins alcoolisées</option>
                </select>
                <br />
                <hr />
                <label for=""> Selection avancée : </label>
                <br />
                <br />
                <input class="option_recherche" placeholder=" Nom bière" name="nomb" type="text" size="20" value=""/>
                <select class='option_recherche' name="type">
                    <option value=""> -- Type --</option>
                    <?php
                    $type = $bd->getType();
                    for ($i = 0; $i < sizeof($type); $i++) {
                        echo "<option value='" . $type[$i] . "'>" . $type[$i] . "</option>";
                    }
                    ?>
                </select>
                <select class='option_recherche' name="mf">
                    <option value=""> -- Mode de fabrication --</option>
                    <?php
                    $mf = $bd->getMF();
                    for ($i = 0; $i < sizeof($mf); $i++) {
                        echo "<option value='" . $mf[$i] . "'>" . $mf[$i] . "</option>";
                    }
                    ?>
                </select>
                <select class='option_recherche' name="marque">
                    <option value=""> -- Marque --</option>
                    <?php
                    $marque = $bd->getMarque();
                    for ($i = 0; $i < sizeof($marque); $i++) {
                        echo "<option value='" . $marque[$i] . "'>" . $marque[$i] . "</option>";
                    }
                    ?>
                </select>

                <hr />
                <input class="button_recherche" type="submit" value="Rechercher" />
            </fieldset>
        </form>

        <!-- Récupération des données du formulaire traitement associé  -->
        <?php
        $type = "%";
        $mf = "%";
        $marque = "%";
        $tri = "";
        $nomb = "%";
        if (isset($_GET['type']) && $_GET['type'] != "") {
            $type = $_GET['type'];
        }
        if (isset($_GET['marque']) && $_GET['marque'] != "") {
            $marque = $_GET['marque'];
        }
        if (isset($_GET['mf']) && $_GET['mf'] != "") {
            $mf = $_GET['mf'];
        }
        if (isset($_GET['nomb']) && $_GET['nomb'] != "") {
            $nomb = $_GET['nomb'];
        }
        if (isset($_GET['tri']) && $_GET['tri'] != "") {
            $tri = "ORDER BY " . $_GET['tri'];
        }
        $bieres = $bd->getBieres($type, $mf, $marque, $nomb,$tri);
        echo "<h3>" . sizeof($bieres) . " Résultats </h3>";
        for ($i = 0; $i < sizeof($bieres); $i++) {
            $bieres[$i]->afficherBiere();
        }

        ?>
    </div>

    <?php
    include("navbar/navbar.php");
    ?>
</body>
