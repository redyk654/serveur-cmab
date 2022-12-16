<?php
    header('Access-Control-Allow-Origin: *');

    //Recupération des medicaments enregistrés dans la base

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if(isset($_GET['stock'])) {
        if($_GET['stock'] == 'filtre') {
            $req = $bdd->query("SELECT * FROM medicaments ORDER BY designation") or die(print_r($bdd->errorInfo()));
            $data = $req->fetchAll();
            
            echo json_encode($data);
        }
    } else {

        $req = $bdd->query("SELECT * FROM medicaments ORDER BY designation") or die(print_r($bdd->errorInfo()));
        $data = $req->fetchAll();
        
        echo json_encode($data);
    }
?>