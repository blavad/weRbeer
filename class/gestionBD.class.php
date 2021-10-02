<?php

require_once('class/utilisateur.class.php');
require_once('class/biere.class.php');
require_once('class/avis.class.php');

class GestionBD
{
    protected $bd;

    public function __construct()
    {
        $servername = "ec2-34-247-151-118.eu-west-1.compute.amazonaws.com";
        $username = "kqnlvupypdvakq";//"id9515413_werbeer";
        $password = "c2645913c2f90a0f49a030598f9881d199c9a34a3942a5f77c8008d020ab3865";
        $database = "d8pa0crf9c52tq";//"id9515413_werbeer";

	$dsn = "pgsql:host=$servername;port=5432;dbname=$database;user=$username;password=$password";
   
        try {
            $this->bd = new PDO($dsn);
	    // set the PDO error mode to exception
            $this -> bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(Exception $e) {    
            echo "Connection failed: ".$e->getMessage();
        }
    }

    function addBiere($data, $file)
    {
        $target_file = "photoB/" . basename($file["photob"]["name"]);
        $err_photo = $this->checkPhotoValide($file["photob"], $target_file);
        $err = $err_photo['err'];
        $errMessage = $err_photo['errMessage'];
        if ($data['type'] == "" || $data['marque'] == "" ||  $data['mf'] == "") {
            $errMessage = "Champs manquant";
            $err = true;
        }
        $req = $this->bd->prepare("SELECT nomb FROM biere WHERE nomb=?;");
        $req->execute(array($data['nomb']));
        if ($req->fetch()) {
            $errMessage = "Bière déjà existante";
            $err = true;
        }
        if (!$err) {
            $errMessage = "Bière ajoutée avec succès ";
            $urlphoto = $file['photob']['name'];
            move_uploaded_file($file['photob']['tmp_name'], $target_file);

            $req = $this->bd->prepare('INSERT INTO Biere(nomb,nommar, nomt, nommf, alcoolemie, urlphoto) VALUES(:nomb,:nommar,:nomt,:nommf,:alcoolemie,:urlphoto)');
            $req->execute(array(
                'nomb' => $data['nomb'],
                'nommar' => $data['marque'],
                'nomt' => $data['type'],
                'nommf' => $data['mf'],
                'alcoolemie' => $data['alco'],
                'urlphoto' => $urlphoto,
            ));
        }
        return array('erreur' => $err, 'errMessage' => $errMessage);
    }

    // Utilisateur et amis 

    function isAllowed($id_u, $id_ami)
    {
        $req = $this->bd->prepare("SELECT * FROM relation WHERE idu1 = ? AND idu2 = ?;");
        $req->execute(array($id_u, $id_ami));

        return $req->fetch() || $id_u == $id_ami;
    }

    function getUtilisateur($id)
    {
        $req = $this->bd->prepare("SELECT * FROM Utilisateur WHERE idu = ?;");
        $req->execute(array($id));

        $req_util = $req->fetch();

        $util = new Utilisateur($req_util['idu'], $req_util['nom'], $req_util['prenom'], $req_util['pseudo'],  $req_util['datenaissance'], $req_util['sexe'], $req_util['urlphoto']);

        $req->closeCursor();

        return $util;
    }

    function getAmis($id, $partName = "%")
    {
        $req = $this->bd->prepare("SELECT idu2 FROM relation r, utilisateur u WHERE r.idu1=? AND r.idu2=u.idu AND (u.prenom LIKE '" . $partName . "%' OR u.nom LIKE '" . $partName . "%' OR u.pseudo LIKE '" . $partName . "%') ORDER BY u.prenom, u.nom, u.pseudo ;");
        $req->execute(array($id));

        $amis = array();
        while ($donnees = $req->fetch()) {
            $amis[] = $this->getUtilisateur($donnees['idu2']);
        }
        $req->closeCursor();

        return $amis;
    }

    function getRelations($id, $partName = "%")
    {
        $req = $this->bd->prepare("SELECT idu1 FROM relation r, utilisateur u WHERE r.idu1=u.idu AND r.idu2=? AND (u.prenom LIKE '" . $partName . "%' OR u.nom LIKE '" . $partName . "%' OR u.pseudo LIKE '" . $partName . "%') ORDER BY u.prenom, u.nom, u.pseudo ;");
        $req->execute(array($id));

        $amis = array();
        while ($donnees = $req->fetch()) {
            $amis[] = $this->getUtilisateur($donnees['idu1']);
        }
        $req->closeCursor();

        return $amis;
    }

    function addAmi($mon_id, $id_ami)
    {
        $req = $this->bd->prepare('INSERT INTO relation(idu1,idu2) VALUES(:idu1, :idu2)');
        $req->execute(array(
            'idu1' => $mon_id,
            'idu2' => $id_ami
        ));
    }

    function supprimerAmi($mon_id, $id_ami)
    {
        $req = $this->bd->prepare('DELETE FROM relation WHERE idu1=:idu1 AND idu2=:idu2');
        $req->execute(array(
            'idu1' => $mon_id,
            'idu2' => $id_ami
        ));
    }


    // Bieres et avis

    function recherche_avis_avancee($select = 'nomt', $id = "-1")
    {
        if ($id == "-1") {
            $table = "";
            switch ($select) {
                case 'nomt':
                    $table = "type";
                    break;
                case 'nommar':
                    $table = "marque";
                    break;
                case 'nommf':
                    $table = "modefabrication";
                    break;
            }
            $req = $this->bd->prepare("SELECT DISTINCT ".$select." FROM ".$table.";");
            $req->execute();
        } else {
            $req = $this->bd->prepare("SELECT DISTINCT ".$select." FROM avis a, biere b WHERE b.nomb=a.nomb AND a.idu=?;");
            $req->execute(array($id));
        }
        $res = array();
        while ($donnees = $req->fetch()) {
            $res[] = $donnees[$select];
        }
        $req->closeCursor();
        return $res;
    }

    function getType($id = "-1")
    {      
	return $this->recherche_avis_avancee("nomt", $id);
    }

    function getMF($id = "-1")
    {
        return $this->recherche_avis_avancee("nommf", $id);
    }

    function getMarque($id = "-1")
    {
        return $this->recherche_avis_avancee("nommar", $id);
    }

    function getBiere($nombiere)
    {
        $req = $this->bd->prepare("SELECT * FROM Biere WHERE nomb = ?;");
        $req->execute(array($nombiere));
        $donnees = $req->fetch();
        $biere = new Biere($donnees['nomb'], $donnees['nomt'], $donnees['nommf'], $donnees['alcoolemie'], $donnees['notemoyenne'], $donnees['nommar'], $donnees['urlphoto']);

        $req = $this->bd->prepare("SELECT COUNT(*) FROM avis WHERE nomb = ?;");
        $req->execute(array($nombiere));
        $donnees = $req->fetch();
        $biere->setNbAvis($donnees['count']);
        $req->closeCursor();

        return $biere;
    }

    function getBieres($type, $mf, $marque, $nomb, $tri)
    {
        $req = $this->bd->prepare("SELECT * FROM biere b WHERE b.nomb LIKE '" . $nomb . "%' AND b.nomt LIKE '" . $type . "' AND b.nommar LIKE '" . $marque . "' AND b.nommf LIKE '" . $mf . "' " . $tri . ";");
        $req->execute();

        $bieres = array();
        while ($donnees = $req->fetch()) {
            $reqAvis = $this->bd->prepare("SELECT COUNT(*) FROM avis a WHERE a.nomb = ?;");
            $reqAvis->execute(array($donnees['nomb']));
            $nbA = $reqAvis->fetch();
            $bieres[] = new Biere($donnees['nomb'], $donnees['nomt'], $donnees['nommf'], $donnees['alcoolemie'], $donnees['notemoyenne'], $donnees['nommar'], $donnees['urlphoto'], $nbA['count']);
        }
        $req->closeCursor();

        return $bieres;
    }

    function getCave($id, $type = "%", $mf = "%", $marque = "%", $nomb = "%", $tri = "")
    {
        $req = $this->bd->prepare("SELECT * FROM avis a, Biere b WHERE b.nomb=a.nomb AND a.idu=? AND b.nomb LIKE '" . $nomb . "%' AND b.nomt LIKE '" . $type . "' AND b.nommar LIKE '" . $marque . "' AND b.nommf LIKE '" . $mf . "' " . $tri . ";");
        $req->execute(array($id));

        $cave = array();
        while ($donnees = $req->fetch()) {
            $cave[] = new Avis($donnees['idu'], new Biere($donnees['nomb'], $donnees['nomt'], $donnees['nommf'], $donnees['alcoolemie'], $donnees['notemoyenne'], $donnees['nommar'], $donnees['urlphoto']), $donnees['note'], $donnees['commentaire']);
        }
        $req->closeCursor();

        return $cave;
    }

    function addAvis($id, $nombiere, $note, $com)
    {
        $req = $this->bd->prepare('INSERT INTO avis(idu,nomb,note,commentaire ) VALUES(:idu, :nomb, :note, :commentaire)');
        $req->execute(array(
            'idu' => $id,
            'nomb' => $nombiere,
            'note' => $note,
            'commentaire' => $com
        ));
    }

    function supprimerAvis($mon_id, $nombiere)
    {
        $req = $this->bd->prepare('DELETE FROM avis WHERE idu=:idu AND nomb=:nomb');
        $req->execute(array(
            'idu' => $mon_id,
            'nomb' => $nombiere
        ));
    }

    // A finir 
    function getActualites($id, $nombre)
    {
        // Récupération des actus relatifs aux derniers avis ajoutés
        $actusAjoutBieres = array();
        $req = $this->bd->prepare("SELECT * FROM relation r, avis a, Biere b WHERE b.nomb=a.nomb AND a.idu=r.idu2 AND r.idu1=:idu;");
        $req->execute(array(
            'idu' => $id
        ));
        while ($donnees = $req->fetch()) {
            $actusAjoutBieres[] = new Avis($donnees['idu'], new Biere($donnees['nomb'], $donnees['nomt'], $donnees['nommf'], $donnees['alcoolemie'], $donnees['notemoyenne'], $donnees['nommar'], $donnees['urlphoto']), $donnees['note'], $donnees['commentaire']);
        }
        $req->closeCursor();

        // Récupération des actus relatifs aux derniers amis qui m'ont suivis     
        $actusAjoutAmis = array();
        $req = $this->bd->prepare("SELECT r.idu1 FROM relation r WHERE r.idu2=?;");
        $req->execute(array($id));
        while ($donnees = $req->fetch()) {
            $actusAjoutAmis[] = $this->getUtilisateur($donnees['idu1']);
        }
        $req->closeCursor();

        $actu = array('avis' => $actusAjoutBieres, 'ami' => $actusAjoutAmis);
        return $actu;
    }

    function getMFDescription($nommf = "inconnu")
    {
        $req = $this->bd->prepare("SELECT description FROM ModeFabrication WHERE nommf = ?;");
        $req->execute(array($nommf));

        $donnee = $req->fetch();

        $res = array('description' => $donnee['description']);
        $req->closeCursor();

        return $res;
    }

    function getMarqueInfos($nommarque = "Heineken")
    {
        $req = $this->bd->prepare("SELECT l.ville, l.pays, m.dateFondation FROM Marque m, Lieu l WHERE l.idloc=m.idloc AND nommar = ?;");
        $req->execute(array($nommarque));

        $donnee = $req->fetch();

        $res = array('lieu' => $donnee['ville'] . ", " . $donnee['pays'], 'annee' => $donnee['datefondation']);
        $req->closeCursor();

        return $res;
    }

    function getLieux()
    {
        $req = $this->bd->prepare("SELECT DISTINCT idloc, ville, region, pays FROM Lieu ORDER BY pays, region, ville;");
        $req->execute();
        $res = array();
        while ($donnees = $req->fetch()) {
            $lieu = array('idloc' => $donnees['idloc'], 'ville' => $donnees['ville'], 'region' => $donnees['region'], 'pays' => $donnees['pays']);
            $res[] = $lieu;
        }
        $req->closeCursor();
        return $res;
    }

    function addLieu($data)
    {
        $errMessage = "Lieu ajouté avec succès";
        $err = false;
        $req = $this->bd->prepare('INSERT INTO Lieu(ville, region, pays) VALUES(:ville, :region, :pays)');
        $req->execute(array(
            'ville' => $data['ville'],
            'region' => $data['region'],
            'pays' => $data['pays']
        ));
        return array('erreur' => $err, 'errMessage' => $errMessage);
    }

    function addMarque($data)
    {
        $errMessage = "Marque ajoutée avec succès";
        $err = false;
	
        if ($data['lieu'] == "") {
            $errMessage = "Champs manquant";
            $err = true;
        }
        $req = $this->bd->prepare("SELECT nommar FROM Marque WHERE nommar=?;");
        $req->execute(array($data['nommar']));
        if ($donnees = $req->fetch()) {
            $errMessage = "Marque déjà existante";
            $err = true;
        }
        if (!$err) {
            $req = $this->bd->prepare('INSERT INTO Marque(nommar, idloc, dateFondation) VALUES(:nommar, :idloc, :annee)');
            $req->execute(array(
                'nommar' => $data['nommar'],
                'idloc' => $data['lieu'],
                'annee' => $data['annee'].'-01-01'
            ));
        }
        return array('erreur' => $err, 'errMessage' => $errMessage);
    }

    function dejaAjouter($nomb, $idu)
    {
        $req = $this->bd->prepare("SELECT * FROM avis WHERE nomb = ? AND idu = ?;");
        $req->execute(array($nomb, $idu));
        return $req->fetch();
    }




    // GESTION DE L'INSCRIPTION ET DES MISES A JOUR DES DONNEES UTILISATEUR

    function connexion($pseudo, $mdp)
    {
        $req = $this->bd->prepare("SELECT idu, mdp FROM Utilisateur WHERE pseudo = ?;");
        $req->execute(array($pseudo));

        $req_mdp = $req->fetch();
        $req->closeCursor();

        $res = array($req_mdp['idu'], $req_mdp['mdp'] == $mdp);

        return $res;
    }

    function inscrire($data, $file)
    {
        $errMessage = "Inscription réussie";
        $err = false;
        $target_dir = "user/" . $data["identifiant"];

        // Check si le pseudo est déjà utilisé
        if (is_dir($target_dir)) {
            $errMessage = "Identifiant déjà existant";
            $err = true;
        } else {
            $target_file = $target_dir . "/" . basename($file["maPhoto"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check si une image a été choisie
            if ($file["maPhoto"]["name"] != "") {
                // Check si l'image est correct
                if (isset($data["submit"])) {
                    $check = getimagesize($file["maPhoto"]["tmp_name"]);
                    if ($check !== false) {
                        $err = false;
                    } else {
                        $errMessage = "Image incorrect";
                        $err = true;
                    }
                }

                // Check si le fichier existe déjà
                if (file_exists($target_file)) {
                    $errMessage = "Fichier image déjà existant";
                    $err = true;
                }
                // Check la dimension de l'image
                if ($file["maPhoto"]["size"] > 30000000) {
                    $errMessage = "Fichier image trop gros";
                    $err = true;
                }
                // Check le bon format de l'image
                if (
                    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif"
                ) {
                    $errMessage = "Format fichier incorrect";
                    $err = true;
                }
            }
            // Si pas d'erreur on crée le dossier utilisateur et on ajoute la photo
            if (!$err) {
                mkdir($target_dir);
                $urlphoto = "";
                if ($file["maPhoto"]["name"] == "") {
                    $urlphoto = "photoProf.png";
                    $target_file = $target_dir . "/" . $urlphoto;
                    copy("img/photoProf.png", $target_file);
                } else {
                    $urlphoto = $file["maPhoto"]["name"];
                    move_uploaded_file($file["maPhoto"]["tmp_name"], $target_file);
                }
                $req = $this->bd->prepare('INSERT INTO Utilisateur(nom,prenom,pseudo,mdp, datenaissance, urlphoto, sexe) VALUES(:nom, :prenom,:pseudo, :mdp, :datenaissance, :urlphoto, :sexe)');
                $req->execute(array(
                    'nom' => $data['name'],
                    'prenom' => $data['surname'],
                    'pseudo' => $data['identifiant'],
                    'mdp' => $data['mdp'],
                    'datenaissance' => $data['datenaissance'],
                    'urlphoto' => $urlphoto,
                    'sexe' => $data['sexe']
                ));
            }
        }
        return array('erreur' => $err, 'errMessage' => $errMessage);
    }

    function modifierMDP($id, $old_mdp, $new_mdp)
    {
        $req = $this->bd->prepare("SELECT u.mdp FROM Utilisateur u WHERE u.idu = ?;");
        $req->execute(array($id));
        $old = $req->fetch()['mdp'];
        if ($old == $old_mdp) {
            $req = $this->bd->prepare("UPDATE Utilisateur u SET u.mdp=:newMDP WHERE u.idu =:idu;");
            $req->execute(array(
                'newMDP' => $new_mdp,
                'idu' => $id
            ));
            return array('erreur' => false, 'errMessage' => "Mot de passe mis à jour.");
        } else {
            return array('erreur' => true, 'errMessage' => "Mot de passe incorrect.");
        }
    }

    function modifierPhoto($id, $file)
    {
        $u = $this->getUtilisateur($id);
        $target_dir = "user/" . $u->getPseudo();
        $target_file = $target_dir . "/" . basename($file["name"]);
        $err_photo = $this->checkPhotoValide($file, $target_file);
        if (!$err_photo['err']) {
            $urlphoto = $file["name"];
            move_uploaded_file($file["tmp_name"], $target_file);
            $req = $this->bd->prepare('UPDATE Utilisateur u SET u.urlphoto=:newURL WHERE u.idu =:idu;');
            $req->execute(array(
                'newURL' => $urlphoto,
                'idu' => $id
            ));
        }
        return array('erreur' => $err_photo['err'], 'errMessage' => $err_photo['errMessage']);
    }


    function checkPhotoValide($file, $target_file)
    {
        $err = false;
        $errMessage = "Image correct";
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check si une image a été choisie
        if ($file["name"] != "") {
            // Check si l'image est correct
            $check = getimagesize($file["tmp_name"]);
            if ($check !== false) {
                $err = false;
            } else {
                $errMessage = "Image incorrect";
                $err = true;
            }

            // Check si le fichier existe déjà
            if (file_exists($target_file)) {
                $errMessage = "Nom de fichier image déjà existant";
                $err = true;
            }
            // Check la dimension de l'image
            if ($file["size"] > 30000000) {
                $errMessage = "Fichier image trop gros";
                $err = true;
            }
            // Check le bon format de l'image
            if (
                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif"
            ) {
                $errMessage = "Format fichier incorrect";
                $err = true;
            }
        }
        return array("err" => $err, "errMessage" => $errMessage);
    }

    function supprimerCompte($id)
    {
        $req = $this->bd->prepare('DELETE FROM avis WHERE idu=:idu');
        $req->execute(array(
            'idu' => $id
        ));

        $req = $this->bd->prepare('DELETE FROM relation WHERE idu1=:idu OR idu2=:idu');
        $req->execute(array(
            'idu' => $id
        ));

        $req = $this->bd->prepare('DELETE FROM Utilisateur WHERE idu=:idu');
        $req->execute(array(
            'idu' => $id
        ));
    }
}
