<?php
require_once('class/utilisateur.class.php');
require_once('class/gestionBD.class.php');

$NB_AFFICHAGE = 4;

$donnees = ["Rion","Marion","Dav","Rithon","Henri","Max","Maxou"];
shuffle($donnees);

$term = $_GET['term'];

$requete = $bdd->prepare('SELECT * FROM membres WHERE pseudo LIKE :term'); 
$requete->execute(array('term' => '%'.$term.'%'));

$array = array();

while($donnee = $requete->fetch()) // on effectue une boucle pour obtenir les données
for($i=0;$i<$NB_AFFICHAGE;$i++)
{
    array_push($array, $donnee['pseudo']); // et on ajoute celles-ci à notre tableau
}
*/

echo json_encode($donnees); 

?>