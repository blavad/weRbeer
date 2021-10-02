<?php

	$servername = "ec2-34-247-151-118.eu-west-1.compute.amazonaws.com";
        $username = "kqnlvupypdvakq";//"id9515413_werbeer";
        $password = "c2645913c2f90a0f49a030598f9881d199c9a34a3942a5f77c8008d020ab3865";
        $database = "d8pa0crf9c52tq";//"id9515413_werbeer";

	$dsn = "pgsql:host=$servername;port=5432;dbname=$database;user=$username;password=$password";
   
        try {
            $db = new PDO($dsn);
            // set the PDO error mode to exception
	    if($db){
  	    	echo "Connected to $database !";
  	    }
        } catch(Exception $e) {    
            echo "Connection failed: ".$e->getMessage();
        }

 $req = $db->prepare("SELECT DISTINCT idLoc, ville, region, pays FROM Lieu ORDER BY pays, region, ville;");
        $req->execute();
        $res = array();
        while ($donnees = $req->fetch()) {
		echo $donnees['idloc'];
		foreach($donnees as $d){
			echo $d . "-";	
		}
	}

?>
