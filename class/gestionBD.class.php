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

    function addBiere($data, $file)
    {
        $errMessage = "Bière ajoutée avec succès";
        $err = false;
        if ($data['type'] == "" || $data['marque'] == "" ||  $data['mf'] == "") {
            $errMessage = "Champs manquant";
            $err = true;
        }
        $req = $this->bd->prepare("SELECT nomB FROM Biere WHERE nomB=?;");
        $req->execute(array($data['nomB']));
        if ($donnees = $req->fetch()) {
            $errMessage = "Bière déjà existante";
            $err = true;
        }
        if (!$err) {
            $urlPhoto = $file["photoB"]["name"];
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

    function getAmis($id, $partName="%")
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

    function getRelations($id){
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
        echo "DELETE " . $mon_id . " " . $nomBiere;
        $req = $this->bd->prepare('DELETE FROM avis WHERE idU=:idU AND nomB=:nomB');
        $req->execute(array(
            'idU' => $mon_id,
            'nomB' => $nomBiere
        ));
    }

    // A finir 
    function getActualites($id, $nombre)
    {
        $req = $this->bd->prepare("SELECT a.idU , a.nomB, a.note, a.commentaire FROM relation r, avis a WHERE a.idU=r.idU2 AND r.idU1=? LIMIT ?;");
        $req->execute(array($id, $nombre));
        $actu = array();
        while ($donnees = $req->fetch()) {
            $actusAjoutBieres[] = new getUtilsateur($donnees['idU2']);
        }
        $req->closeCursor();

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

    function dejaAjouter($nomB,$idU){
        $req = $this->bd->prepare("SELECT * FROM avis WHERE nomB = ? AND idU = ?;");
        $req->execute(array($nomB, $idU));
        return $req->fetch();
    }
}
