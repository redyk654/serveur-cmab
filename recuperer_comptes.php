<?php
    header('Access-Control-Allow-Origin: *');

    //Recupération des comptes pour verification lors de la connexion

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    $req = $bdd->query("SELECT id, nom_user, rol FROM utilisateurs") or die(print_r($bdd->errorInfo()));

    $data = $req->fetchAll();

    echo json_encode($data);
?>