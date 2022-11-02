<?php
    header('Access-Control-Allow-Origin: *');

    //Recupération des services

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    
    }
    if (isset($_GET['urgence'])) {
        $req = $bdd->query("SELECT * FROM services_urgences ORDER BY designation") or die(print_r($bdd->errorInfo()));
        $data = $req->fetchAll();
        
        echo json_encode($data);
    } else {
        $req = $bdd->query("SELECT * FROM services ORDER BY designation") or die(print_r($bdd->errorInfo()));
        $data = $req->fetchAll();
        
        echo json_encode($data);
    }
?>