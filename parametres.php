<?php
require_once('class/utilisateur.class.php');
require_once('class/biere.class.php');
require_once('class/avis.class.php');
require_once('class/gestionBD.class.php');
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>

<!DOCTYPE html>
<html>

<head>
	<title>weRbeer -- Paramètres</title>
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
		<table>
			<tr>
				<td>
					<form method="post" action="parametres.php" enctype="multipart/form-data">
						<fieldset id='co'>
							<legend> Parametres du compte </legend>

							<label for="mdp "> Ancien mot de passe : </label>
							<br />
							<input type="password" placeholder="Saisissez votre mot de passe" name="mdp_old" size="30">
							<br />
							<label for="mdp "> Nouveau mot de passe : </label>
							<br />
							<input type="password" placeholder="Saisissez votre nouveau mot de passe" name="mdp_new" size="30">
							<br />
							<label for="mdp "> Confirmation du nouveau mot de passe: </label>
							<br />
							<input type="password" placeholder="Saisissez votre nouveau mot de passe" name="mdp_new2" size="30">
							<br /><br />
							<label for="photo "> Changement photo :</label>
							<br />
							<input type="file" name="newPhoto" />

						</fieldset>
						<input class="bouton" type="submit" value="Valider" />
						<input class="bouton" type="reset" value="Effacer " />

					</form>

				</td>
				<td>
					<form method="post" action="index.php" enctype="multipart/form-data">
						<fieldset id="co">
							<legend> Supression du compte </legend>
							<input class="bouton" type="submit" name="suppCompte" value="Supprimer" />
						</fieldset>

					</form>
				</td>
			</tr>
		</table>
	</div>

	<?php

	function messageResultatAjout($msg, $echec = true)
	{
		$color = ($echec) ? "red" : "green";
		return "<div class='max-width' style='position: fixed; background:" . $color . "; color : white; bottom:40px; text-align:center;'> 
            " . $msg . " </div>";
	}

	$bd = new GestionBD();
	if (isset($_POST)) {
		$msgErr = NULL;
		if (isset($_POST['mdp_old']) && isset($_POST['mdp_new']) && isset($_POST['mdp_new2']) && $_POST['mdp_old'] != "" && $_POST['mdp_new'] != "" && $_POST['mdp_new2'] != "") {
			if ($_POST['mdp_new'] == $_POST['mdp_new2']) {
				$msgErr = $bd->modifierMDP($_SESSION['util']->getId(), $_POST['mdp_old'], $_POST['mdp_new']);
			} else {
				$msgErr = array('erreur' => true, 'errMessage' => "Erreur de saisie du nouveau mot de passe.");
			}
			echo messageResultatAjout($msgErr['errMessage'], $msgErr['erreur']);
		}
		if (isset($_FILES['newPhoto'])) {
			$msgErr = $bd->modifierPhoto($_SESSION['util']->getId(), $_FILES['newPhoto']);
			if (!$msgErr['erreur']) {
				$_SESSION['util']->setUrlPhoto($_FILES['newPhoto']['name']);
			}
			echo messageResultatAjout($msgErr['errMessage'], $msgErr['erreur']);
		}
	}

	?>
	<?php include("navbar/navbar.php"); ?>
</body>

</html>