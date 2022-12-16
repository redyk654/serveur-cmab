<?php
    header('Access-Control-Allow-Origin: *');

    //Recupération les fournisseurs dans la base de donnnées

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    $req = $bdd->query("SELECT * FROM fournisseurs") or die(print_r($bdd->errorInfo()));
    $data = $req->fetchAll();
    
    echo json_encode($data);
?>