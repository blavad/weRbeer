<?php
session_start();
require_once('class/utilisateur.class.php');
$_SESSION['util'] = new Utilisateur(5,"Davdav","img/prof.png");
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
  <div class="main-content">
    <h1> Site bière</h1>
  </div>
</body>

</html>
