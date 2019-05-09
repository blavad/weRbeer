<?php

session_start();

$NB_AFFICHAGE = 3;

$servername = "127.0.0.1";
$username = "weRbeer"; //"id9515413_werbeer";
$password = "frosties";
$database = "weRbeer"; //"id9515413_werbeer";

try {
    $bd = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
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
        $u = array('type' => "u", 'value' => $donnee['idU'], 'label' => htmlspecialchars($label), 'icon' => 'user/' . $donnee['pseudo'] . '/' . $donnee['urlPhoto']);
        array_push($array, $u);
        $i++;
    }
}

$i = (isset($_SESSION['admin'])) ? -3 : 0;

$req = $bd->prepare('SELECT * FROM Biere WHERE nomB LIKE :term ORDER BY nomB');
$req->execute(array('term' => $term . '%'));

while (($donnee = $req->fetch()) && ($i < $NB_AFFICHAGE)) {
    $label = $donnee['nomB'];
    $u = array('type' => "b", 'value' => $donnee['nomB'], 'label' => htmlspecialchars($label), 'icon' => 'photoB/' . $donnee['urlPhoto']);
    array_push($array, $u);
    $i++;
}

$label = "Autres bières...";
$u = array('type' => "o", 'value' => "", 'label' => htmlspecialchars($label), 'icon' => 'img/plus.png');
array_push($array, $u);

echo json_encode($array);
