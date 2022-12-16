<?php
    header('Access-Control-Allow-Origin: *');

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['designation']) AND isset($_POST['prix']) AND isset($_POST['categorie'])) {
        // Insertion d'un nouveau service

        $req = $bdd->prepare("INSERT INTO services(designation, prix, categorie) VALUES(?, ?, ?)") or die(print_r($bdd->errorInfo()));
        $req->execute(
            array(
                $_POST['designation'],
                $_POST['prix'],
                $_POST['categorie'],
            )
        );

        $req = $bdd->prepare("INSERT INTO services_urgences(designation, prix, categorie) VALUES(?, ?, ?)") or die(print_r($bdd->errorInfo()));
        $req->execute(
            array(
                $_POST['designation'],
                $_POST['prix'],
                $_POST['categorie'],
            )
        );
    }
?>