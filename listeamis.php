<?php
require_once('class/utilisateur.class.php');
require_once('class/gestionBD.class.php');
?>

<!DOCTYPE html>
<html>

<head>
    <title>Mes amis</title>
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
        <h1> Mes amis </h1>
        <table class="liste">
        <tr id="la">
            <td id="petit">
                <img src="photoU/photo_david.png" width=60px height=60px/>
            </td>
            <td>
                <label for="pseudo"> Pseudo </label>
            </td>
            <td id="petit">
            <label for="suppr"> &#10060 </label>
            </td>
        </tr>
        </table>
        <table class="liste">
        <tr id="la">
            <td id="petit">
                <img src="photoU/photo_marion.png" width=60px height=60px/>
            </td>
            <td>
                <label for="pseudo"> Pseudo </label>
            </td>
            <td id="petit">
            <label for="suppr"> &#10060 </label>
            </td>
        </tr>
        </table>
        
    </div>
    <?php 
   include("navbar/navbar.php");
    ?>
</body>

</html>