<?php

$NB_AFFICHAGE = 3;

$server = "127.0.0.1";
$username = "weRbeer";
$password = "frosties";
try {
    $bd = new PDO('mysql:dbname=weRbeer;host=' . $server . ';charset=utf8;port=3306', $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $err) {
    die('Erreur : ' . $err->getMessage());
}
$term = $_GET['term'];

$req = $bd->prepare('SELECT * FROM Utilisateur WHERE pseudo LIKE :term ORDER BY pseudo');
$req->execute(array('term' => $term . '%'));

$array = [];
$i = 0;

while (($donnee = $req->fetch()) && ($i < $NB_AFFICHAGE)) {
    $label = $donnee['pseudo'] . ' -- ' . $donnee['idU'];
    $u = array('type' => "u", 'value' => $donnee['idU'], 'label' => $label, 'icon' => 'photoU/' . $donnee['urlPhoto']);
    array_push($array, $u);
    $i++;
}

$req = $bd->prepare('SELECT * FROM Biere WHERE nomB LIKE :term ORDER BY nomB');
$req->execute(array('term' => $term . '%'));

$i = 0;
while (($donnee = $req->fetch()) && ($i < $NB_AFFICHAGE)) {
    $label = $donnee['nomB'];
    $u = array('type' => "b", 'value' => $donnee['nomB'], 'label' => $label, 'icon' => 'photoB/' . $donnee['urlPhoto']);
    array_push($array, $u);
    $i++;
}

$label = "Autre ...";
$u = array('type' => "o", 'value' => "", 'label' => $label, 'icon' => 'img/plus.png');
array_push($array, $u);

echo json_encode($array);
