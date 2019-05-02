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

        $util = new Utilisateur($req_util['idU'], $req_util['pseudo'], $req_util['sexe'], $req_util['dateNaissance'], $req_util['urlPhoto']);

        $req->closeCursor();

        return $util;
    }

    function getAmis($id)
    {
        $req = $this->bd->prepare("SELECT idU2 FROM relation r WHERE r.idU1=?;");
        $req->execute(array($id));

        $amis = array();
        while ($donnees = $req->fetch()) {
            $amis[] = new getUtilsateur($donnees['idU2']);
        }
        $req->closeCursor();

        return $amis;
    }

    function recherche_avis_avancee($select = 'nomT', $id = '-1')
    {
        if ($id == "-1") {
            $req = $this->bd->prepare("SELECT DISTINCT " . $select . " FROM FROM avis a, Biere b WHERE b.nomB=a.nomB;");
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

    function getCave($id,$type="%",$mf="%",$marque="%", $tri="")
    {
        $req = $this->bd->prepare("SELECT * FROM avis a, Biere b WHERE b.nomB=a.nomB AND a.idU=? AND b.nomT LIKE '".$type."' AND b.nomMar LIKE '".$marque."' AND b.nomMF LIKE '".$mf."' ".$tri.";");
        $req->execute(array($id));

        $cave = array();
        while ($donnees = $req->fetch()) {
            $cave[] = new Avis($donnees['idU'],new Biere($donnees['nomB'], $donnees['alcoolemie'], $donnees['urlPhoto']), $donnees['note'], $donnees['commentaire']);
        }
        $req->closeCursor();

        return $cave;
    }

    function getBiere($nomBiere)
    {
        $req = $this->bd->prepare("SELECT * FROM Biere WHERE nomB = ?;");
        $req->execute(array($nomBiere));

        $req_biere = $req->fetch();

        $biere = new Biere($req_biere['nomB'], $req_biere['alcoolemie'], $req_biere['urlPhoto']);
        $req->closeCursor();

        return $biere;
    }

    // A finir 
    function getActualites($id, $nombre)
    {
        $req = $this->bd->prepare("SELECT a.idU , a.nomB, a.note, a.commentaire FROM relation r, avis a WHERE a.idU=r.idU2 AND r.idU1=? LIMIT ?;");

        $req->execute(array($id, $nombre));

        $actu = array();
        while ($donnees = $req->fetch()) {
            // $actu[] = new getUtilsateur($donnees['idU2']);
        }
        $req->closeCursor();

        return $actu;
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
        echo "DELETE ".$mon_id." ".$nomBiere;
        /* $req = $this->bd->prepare('DELETE FROM avis WHERE idU=:idU AND nomB=:nomB');
        $req->execute(array(
            'idU' => $mon_id,
            'nomB' => $nomBiere
        )); */
    }
}
