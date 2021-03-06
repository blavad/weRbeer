<?php

session_start();

$NB_AFFICHAGE = 3;

$servername = ""; 
$username   = ""; 
$password   = ""; 
$database   = ""; 

$dsn = "pgsql:host=$servername;port=5432;dbname=$database;user=$username;password=$password";

try {
    $bd = new PDO($dsn);
    // set the PDO error mode to exception
    $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$term = $_GET['term'];
$array = [];
$i = 0;

if (!isset($_SESSION['admin'])) {
    $req = $bd->prepare('SELECT * FROM Utilisateur WHERE pseudo LIKE :term OR prenom LIKE :term OR nom LIKE :term ORDER BY prenom, nom, pseudo');
    $req->execute(array('term' => $term . '%'));

    while (($donnee = $req->fetch()) && ($i < $NB_AFFICHAGE)) {
        $label = $donnee['prenom'] . ' ' . $donnee['nom'] . ' (' . $donnee['pseudo'] . ')';
        $u = array('type' => "u", 'value' => $donnee['idu'], 'label' => htmlspecialchars($label), 'icon' => 'user/' . $donnee['pseudo'] . '/' . $donnee['urlphoto']);
        array_push($array, $u);
        $i++;
    }
}

$i = (isset($_SESSION['admin'])) ? -3 : 0;

$req = $bd->prepare('SELECT * FROM Biere WHERE nomb LIKE :term ORDER BY nomb');
$req->execute(array('term' => $term . '%'));

while (($donnee = $req->fetch()) && ($i < $NB_AFFICHAGE)) {
    $label = $donnee['nomb'];
    $u = array('type' => "b", 'value' => $donnee['nomb'], 'label' => htmlspecialchars($label), 'icon' => 'photoB/' . $donnee['urlphoto']);
    array_push($array, $u);
    $i++;
}

$label = "Autres bières...";
$u = array('type' => "o", 'value' => "", 'label' => htmlspecialchars($label), 'icon' => 'img/plus.png');
array_push($array, $u);

echo json_encode($array);
