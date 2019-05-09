<?php

require_once('class/utilisateur.class.php');
require_once('class/biere.class.php');
require_once('class/avis.class.php');

class GestionBD
{
    protected $bd;

    public function __construct()
    {
        $server = "127.0.0.1";
        $username = "weRbeer";
        $password = "frosties";
        try {
            $this->bd = new PDO('mysql:dbname=weRbeer;host=' . $server . ';charset=utf8;port=3306', $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (Exception $err) {
            die('Erreur : ' . $err->getMessage());
        }
    }

    function addBiere($data, $file)
    {
        $target_file = "photoB/" . basename($file["photoB"]["name"]);
        $err_photo = $this->checkPhotoValide($file["photoB"], $target_file);
        $err = $err_photo['err'];
        $errMessage = $err_photo['errMessage'];
        if ($data['type'] == "" || $data['marque'] == "" ||  $data['mf'] == "") {
            $errMessage = "Champs manquant";
            $err = true;
        }
        $req = $this->bd->prepare("SELECT nomB FROM Biere WHERE nomB=?;");
        $req->execute(array($data['nomB']));
        if ($req->fetch()) {
            $errMessage = "Bière déjà existante";
            $err = true;
        }
        if (!$err) {
            $errMessage = "Bière ajoutée avec succès";
            $urlPhoto = $file["photoB"]["name"];
            move_uploaded_file($file["photoB"]["tmp_name"], $target_file);

            $req = $this->bd->prepare('INSERT INTO Biere(nomB,nomMar, nomT, nomMF, alcoolemie, urlPhoto) VALUES(:nomB,:nomMar,:nomT,:nomMF,:alcoolemie,:urlPhoto)');
            $req->execute(array(
                'nomB' => $data['nomB'],
                'nomMar' => $data['marque'],
                'nomT' => $data['type'],
                'nomMF' => $data['mf'],
                'alcoolemie' => $data['alco'],
                'urlPhoto' => $urlPhoto,
            ));
        }
        return array('erreur' => $err, 'errMessage' => $errMessage);
    }

    // Utilisateur et amis 

    function isAllowed($id_u, $id_ami)
    {
        $req = $this->bd->prepare("SELECT * FROM relation WHERE idU1 = ? AND idU2 = ?;");
        $req->execute(array($id_u, $id_ami));

        return $req->fetch() || $id_u == $id_ami;
    }

    function getUtilisateur($id)
    {
        $req = $this->bd->prepare("SELECT * FROM Utilisateur WHERE idU = ?;");
        $req->execute(array($id));

        $req_util = $req->fetch();

        $util = new Utilisateur($req_util['idU'], $req_util['nom'], $req_util['prenom'], $req_util['pseudo'],  $req_util['dateNaissance'], $req_util['sexe'], $req_util['urlPhoto']);

        $req->closeCursor();

        return $util;
    }

    function getAmis($id, $partName = "%")
    {
        $req = $this->bd->prepare("SELECT idU2 FROM relation r, Utilisateur u WHERE r.idU1=? AND r.idU2=u.idU AND (u.prenom LIKE '" . $partName . "%' OR u.nom LIKE '" . $partName . "%' OR u.pseudo LIKE '" . $partName . "%') ORDER BY u.prenom, u.nom, u.pseudo ;");
        $req->execute(array($id));

        $amis = array();
        while ($donnees = $req->fetch()) {
            $amis[] = $this->getUtilisateur($donnees['idU2']);
        }
        $req->closeCursor();

        return $amis;
    }

    function getRelations($id, $partName = "%")
    {
        $req = $this->bd->prepare("SELECT idU1 FROM relation r, Utilisateur u WHERE r.idU1=u.idU AND r.idU2=? AND (u.prenom LIKE '" . $partName . "%' OR u.nom LIKE '" . $partName . "%' OR u.pseudo LIKE '" . $partName . "%') ORDER BY u.prenom, u.nom, u.pseudo ;");
        $req->execute(array($id));

        $amis = array();
        while ($donnees = $req->fetch()) {
            $amis[] = $this->getUtilisateur($donnees['idU1']);
        }
        $req->closeCursor();

        return $amis;
    }

    function addAmi($mon_id, $id_ami)
    {
        $req = $this->bd->prepare('INSERT INTO relation(idU1,idU2) VALUES(:idU1, :idU2)');
        $req->execute(array(
            'idU1' => $mon_id,
            'idU2' => $id_ami
        ));
    }

    function supprimerAmi($mon_id, $id_ami)
    {
        $req = $this->bd->prepare('DELETE FROM relation WHERE idU1=:idU1 AND idU2=:idU2');
        $req->execute(array(
            'idU1' => $mon_id,
            'idU2' => $id_ami
        ));
    }


    // Bieres et avis

    function recherche_avis_avancee($select = 'nomT', $id = "-1")
    {
        if ($id == "-1") {
            $table = "";
            switch ($select) {
                case 'nomT':
                    $table = "Type";
                    break;
                case 'nomMar':
                    $table = "Marque";
                    break;
                case 'nomMF':
                    $table = "ModeFabrication";
                    break;
            }
            $req = $this->bd->prepare("SELECT DISTINCT " . $select . " FROM " . $table . ";");
            $req->execute();
        } else {
            $req = $this->bd->prepare("SELECT DISTINCT " . $select . " FROM avis a, Biere b WHERE b.nomB=a.nomB AND a.idU=?;");
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
        return $this->recherche_avis_avancee("nomT", $id);
    }

    function getMF($id = "-1")
    {
        return $this->recherche_avis_avancee("nomMF", $id);
    }

    function getMarque($id = "-1")
    {
        return $this->recherche_avis_avancee("nomMar", $id);
    }

    function getBiere($nomBiere)
    {
        $req = $this->bd->prepare("SELECT * FROM Biere WHERE nomB = ?;");
        $req->execute(array($nomBiere));
        $donnees = $req->fetch();
        $biere = new Biere($donnees['nomB'], $donnees['nomT'], $donnees['nomMF'], $donnees['alcoolemie'], $donnees['noteMoyenne'], $donnees['nomMar'], $donnees['urlPhoto']);

        $req = $this->bd->prepare("SELECT COUNT(*) FROM avis WHERE nomB = ?;");
        $req->execute(array($nomBiere));
        $donnees = $req->fetch();
        $biere->setNbAvis($donnees['COUNT(*)']);
        $req->closeCursor();

        return $biere;
    }

    function getBieres($type, $mf, $marque, $nomB, $tri)
    {
        $req = $this->bd->prepare("SELECT * FROM Biere b WHERE b.nomB LIKE '" . $nomB . "%' AND b.nomT LIKE '" . $type . "' AND b.nomMar LIKE '" . $marque . "' AND b.nomMF LIKE '" . $mf . "' " . $tri . ";");
        $req->execute(array($type, $mf, $marque, $tri));

        $bieres = array();
        while ($donnees = $req->fetch()) {
            $reqAvis = $this->bd->prepare("SELECT COUNT(*) FROM avis a WHERE a.nomB = ?;");
            $reqAvis->execute(array($donnees['nomB']));
            $nbA = $reqAvis->fetch();
            $bieres[] = new Biere($donnees['nomB'], $donnees['nomT'], $donnees['nomMF'], $donnees['alcoolemie'], $donnees['noteMoyenne'], $donnees['nomMar'], $donnees['urlPhoto'], $nbA['COUNT(*)']);
        }
        $req->closeCursor();

        return $bieres;
    }

    function getCave($id, $type = "%", $mf = "%", $marque = "%", $nomB = "%", $tri = "")
    {
        $req = $this->bd->prepare("SELECT * FROM avis a, Biere b WHERE b.nomB=a.nomB AND a.idU=? AND b.nomB LIKE '" . $nomB . "%' AND b.nomT LIKE '" . $type . "' AND b.nomMar LIKE '" . $marque . "' AND b.nomMF LIKE '" . $mf . "' " . $tri . ";");
        $req->execute(array($id));

        $cave = array();
        while ($donnees = $req->fetch()) {
            $cave[] = new Avis($donnees['idU'], new Biere($donnees['nomB'], $donnees['nomT'], $donnees['nomMF'], $donnees['alcoolemie'], $donnees['noteMoyenne'], $donnees['nomMar'], $donnees['urlPhoto']), $donnees['note'], $donnees['commentaire']);
        }
        $req->closeCursor();

        return $cave;
    }

    function addAvis($id, $nomBiere, $note, $com)
    {
        $req = $this->bd->prepare('INSERT INTO avis(idU,nomB,note,commentaire ) VALUES(:idU, :nomB, :note, :commentaire)');
        $req->execute(array(
            'idU' => $id,
            'nomB' => $nomBiere,
            'note' => $note,
            'commentaire' => $com
        ));
    }

    function supprimerAvis($mon_id, $nomBiere)
    {
        $req = $this->bd->prepare('DELETE FROM avis WHERE idU=:idU AND nomB=:nomB');
        $req->execute(array(
            'idU' => $mon_id,
            'nomB' => $nomBiere
        ));
    }

    // A finir 
    function getActualites($id, $nombre)
    {
        // Récupération des actus relatifs aux derniers avis ajoutés
        $actusAjoutBieres = array();
        $req = $this->bd->prepare("SELECT * FROM relation r, avis a, Biere b WHERE b.nomB=a.nomB AND a.idU=r.idU2 AND r.idU1=:idU;");
        $req->execute(array(
            'idU' => $id
        ));
        while ($donnees = $req->fetch()) {
            $actusAjoutBieres[] = new Avis($donnees['idU'], new Biere($donnees['nomB'], $donnees['nomT'], $donnees['nomMF'], $donnees['alcoolemie'], $donnees['noteMoyenne'], $donnees['nomMar'], $donnees['urlPhoto']), $donnees['note'], $donnees['commentaire']);
        }
        $req->closeCursor();

        // Récupération des actus relatifs aux derniers amis qui m'ont suivis     
        $actusAjoutAmis = array();
        $req = $this->bd->prepare("SELECT r.idU1 FROM relation r WHERE r.idU2=?;");
        $req->execute(array($id));
        while ($donnees = $req->fetch()) {
            $actusAjoutAmis[] = $this->getUtilisateur($donnees['idU1']);
        }
        $req->closeCursor();

        $actu = array('avis' => $actusAjoutBieres, 'ami' => $actusAjoutAmis);
        return $actu;
    }

    function getMFDescription($nomMF = "inconnu")
    {
        $req = $this->bd->prepare("SELECT description FROM ModeFabrication WHERE nomMF = ?;");
        $req->execute(array($nomMF));

        $donnee = $req->fetch();

        $res = array('description' => $donnee['description']);
        $req->closeCursor();

        return $res;
    }

    function getMarqueInfos($nomMarque = "Heineken")
    {
        $req = $this->bd->prepare("SELECT l.ville, l.pays, m.dateFondation FROM Marque m, Lieu l WHERE l.idLoc=m.idLoc AND nomMar = ?;");
        $req->execute(array($nomMarque));

        $donnee = $req->fetch();

        $res = array('lieu' => $donnee['ville'] . ", " . $donnee['pays'], 'annee' => $donnee['dateFondation']);
        $req->closeCursor();

        return $res;
    }

    function getLieux()
    {
        $req = $this->bd->prepare("SELECT DISTINCT idLoc, ville, region, pays FROM Lieu ORDER BY pays, region, ville;");
        $req->execute();
        $res = array();
        while ($donnees = $req->fetch()) {
            $lieu = array('idLoc' => $donnees['idLoc'], 'ville' => $donnees['ville'], 'region' => $donnees['region'], 'pays' => $donnees['pays']);
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
        $req = $this->bd->prepare("SELECT nomMar FROM Marque WHERE nomMar=?;");
        $req->execute(array($data['nomMar']));
        if ($donnees = $req->fetch()) {
            $errMessage = "Marque déjà existante";
            $err = true;
        }
        if (!$err) {
            $req = $this->bd->prepare('INSERT INTO Marque(nomMar, idLoc, dateFondation) VALUES(:nomMar, :idLoc, :annee)');
            $req->execute(array(
                'nomMar' => $data['nomMar'],
                'idLoc' => $data['lieu'],
                'annee' => $data['annee']
            ));
        }
        return array('erreur' => $err, 'errMessage' => $errMessage);
    }

    function dejaAjouter($nomB, $idU)
    {
        $req = $this->bd->prepare("SELECT * FROM avis WHERE nomB = ? AND idU = ?;");
        $req->execute(array($nomB, $idU));
        return $req->fetch();
    }




    // GESTION DE L'INSCRIPTION ET DES MISES A JOUR DES DONNEES UTILISATEUR

    function connexion($pseudo, $mdp)
    {
        $req = $this->bd->prepare("SELECT idU, mdp FROM Utilisateur WHERE pseudo = ?;");
        $req->execute(array($pseudo));

        $req_mdp = $req->fetch();
        $req->closeCursor();

        $res = array($req_mdp['idU'], $req_mdp['mdp'] == $mdp);

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
                $urlPhoto = "";
                if ($file["maPhoto"]["name"] == "") {
                    $urlPhoto = "photoProf.png";
                    $target_file = $target_dir . "/" . $urlPhoto;
                    copy("img/photoProf.png", $target_file);
                } else {
                    $urlPhoto = $file["maPhoto"]["name"];
                    move_uploaded_file($file["maPhoto"]["tmp_name"], $target_file);
                }
                $req = $this->bd->prepare('INSERT INTO Utilisateur(nom,prenom,pseudo,mdp, dateNaissance, urlPhoto, sexe) VALUES(:nom, :prenom,:pseudo, :mdp, :dateNaissance, :urlPhoto, :sexe)');
                $req->execute(array(
                    'nom' => $data['name'],
                    'prenom' => $data['surname'],
                    'pseudo' => $data['identifiant'],
                    'mdp' => $data['mdp'],
                    'dateNaissance' => $data['dateNaissance'],
                    'urlPhoto' => $urlPhoto,
                    'sexe' => $data['sexe']
                ));
            }
        }
        return array('erreur' => $err, 'errMessage' => $errMessage);
    }

    function modifierMDP($id, $old_mdp, $new_mdp)
    {
        $req = $this->bd->prepare("SELECT u.mdp FROM Utilisateur u WHERE u.idU = ?;");
        $req->execute(array($id));
        $old = $req->fetch()['mdp'];
        if ($old == $old_mdp) {
            $req = $this->bd->prepare("UPDATE Utilisateur u SET u.mdp=:newMDP WHERE u.idU =:idU;");
            $req->execute(array(
                'newMDP' => $new_mdp,
                'idU' => $id
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
            $urlPhoto = $file["name"];
            move_uploaded_file($file["tmp_name"], $target_file);
            $req = $this->bd->prepare('UPDATE Utilisateur u SET u.urlPhoto=:newURL WHERE u.idU =:idU;');
            $req->execute(array(
                'newURL' => $urlPhoto,
                'idU' => $id
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
        $req = $this->bd->prepare('DELETE FROM avis WHERE idU=:idU');
        $req->execute(array(
            'idU' => $id
        ));

        $req = $this->bd->prepare('DELETE FROM relation WHERE idU1=:idU OR idU2=:idU');
        $req->execute(array(
            'idU' => $id
        ));

        $req = $this->bd->prepare('DELETE FROM Utilisateur WHERE idU=:idU');
        $req->execute(array(
            'idU' => $id
        ));
    }
}
