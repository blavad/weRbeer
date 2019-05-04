<!DOCTYPE html>
<html>

<head>
    <title>weRbeer -- Ajout Bières</title>
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
        echo "<h1> Ajout de Bières </h1>"; ?>
        <!-- Formulaire d'ajout de bières -->
        <form method="post" action="index.php">
            <fieldset>
                <hr />
                <label for="nomB"> Nom : </label>
                <br />
                <br />
                <input class='option_recherche' placeholder=" Nom de la bière" id="nomB" name="nomB" type="text" size="30" required />
                <br />
                <label for="alco"> Alcoolémie : </label>
                <br />
                <br />
                <input class='option_recherche' placeholder=" Alcoolémie" type="number" name="alco" step="0.1" required>
                <br />
                <br />
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
                <br />
                <br />
                <label for="photoBiere"> Photo Biere :</label>
                <input type="file" name="photoBiere" id="photo" required />
                <br />
                <input class="bouton" type="submit" value="Ajouter " />
                <input class="bouton" type="reset" value="Annuler " />
            </fieldset>
        </form>        
    </div>
</body>