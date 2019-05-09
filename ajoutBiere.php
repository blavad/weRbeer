<!DOCTYPE html>
<html>

<head>
    <title>weRbeer -- Ajout Données</title>
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
        $bd = new GestionBD(); ?>
        <!-- Formulaire d'ajout de bières -->
        <form method="post" action="index.php" enctype="multipart/form-data">
            <h1> Ajout de Bières </h1>
            <fieldset>
                <hr />
                <label for="nomB"> Nom : </label>
                <br />
                <br />
                <input class='option_recherche' placeholder="Nom de la bière" id="nomB" name="nomB" type="text" size="30" required />
                <br />
                <label for="alco"> Alcoolémie : </label>
                <br />
                <br />
                <input class='option_recherche' placeholder="Alcoolémie" type="number" name="alco" step="0.1" required>
                <br />
                <br />
                <select class='option_recherche' name="type">
                    <option value="">-- Type --</option>
                    <?php
                    $type = $bd->getType();
                    for ($i = 0; $i < sizeof($type); $i++) {
                        echo "<option value='" . $type[$i] . "'>" . $type[$i] . "</option>";
                    }
                    ?>
                </select>
                <select class='option_recherche' name="mf">
                    <option value="">-- Mode de fabrication --</option>
                    <?php
                    $mf = $bd->getMF();
                    for ($i = 0; $i < sizeof($mf); $i++) {
                        echo "<option value='" . $mf[$i] . "'>" . $mf[$i] . "</option>";
                    }
                    ?>
                </select>
                <select class='option_recherche' name="marque">
                    <option value="">-- Marque --</option>
                    <?php
                    $marque = $bd->getMarque();
                    for ($i = 0; $i < sizeof($marque); $i++) {
                        echo "<option value='" . $marque[$i] . "'>" . $marque[$i] . "</option>";
                    }
                    ?>
                </select>
                <br />
                <br />
                <label for="photoB"> Photo Biere :</label>
                <input type="file" name="photoB" required />
                <br />
                <br />
            </fieldset>
            <input class="bouton" type="submit" value="Ajouter " />
            <input class="bouton" type="reset" value="Annuler " />
        </form>
        <br />
        <br />

        <!-- Formulaire d'ajout de Marques -->
        <form method="post" action="index.php">
            <h1> Ajout de Marques </h1>
            <fieldset>
                <hr />
                <label for="nomMar"> Nom : </label>
                <br />
                <br />
                <input class='option_recherche' placeholder="Nom de la marque" name="nomMar" type="text" size="30" value="" required />
                <br />
                <label for="alco"> Année et Lieu de création : </label>
                <br />
                <br />
                <input class='option_recherche' placeholder="Année" type="number" name="annee" value="" required />

                <select class='option_recherche' name="lieu">
                    <option value="">-- Lieu --</option>
                    <?php
                    $lieu = $bd->getLieux();
                    for ($i = 0; $i < sizeof($lieu); $i++) {
                        echo "<option value='" . $lieu[$i]['idLoc'] . "'>" . $lieu[$i]['pays'] . ", " . $lieu[$i]['region'] . ", " . $lieu[$i]['ville'] . "</option>";
                    }
                    ?>
                </select>

                <br />
                <br />

            </fieldset>
            <input class="bouton" type="submit" value="Ajouter " />
            <input class="bouton" type="reset" value="Annuler " />
        </form>

        <br />
        <br />

        <!-- Formulaire d'ajout de Lieux -->
        <form method="post" action="index.php">
            <h1> Ajout de Lieux </h1>
            <hr />
            <fieldset style="margin-left:30%;">
                <input class='option_recherche' placeholder="Ville" name="ville" type="text" size="30" equired />
                <br />
                <input class='option_recherche' placeholder="Region" name="region" type="text" size="30" required />
                <br />
                <input class='option_recherche' placeholder="Pays" name="pays" type="text" size="30" required />
                <br />
                <br />
            </fieldset>
            <input class="bouton" type="submit" value="Ajouter " />
            <input class="bouton" type="reset" value="Annuler " />
        </form>
    </div>
</body>