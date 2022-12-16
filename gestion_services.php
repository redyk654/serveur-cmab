<?php
    header('Access-Control-Allow-Origin: *');

    //Recupération des services

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    
    }
    if (isset($_POST['categorie']) AND isset($_POST['id'])) {
        $req1 = $bdd->prepare("UPDATE services SET categorie = ? WHERE id = ?") or die(print_r($bdd->errorInfo()));
        $req1->execute(
            array(
                $_POST['categorie'],
                $_POST['id'],
            )
        );

        $req2 = $bdd->prepare("UPDATE services_urgences SET categorie = ? WHERE id = ?") or die(print_r($bdd->errorInfo()));
        $req2->execute(
            array(
                $_POST['categorie'],
                $_POST['id'],
            )
        );
    } else if (isset($_GET['id'])) {
        $req1 = $bdd->prepare("DELETE FROM services WHERE id = ?") or die(print_r($bdd->errorInfo()));
        $req1->execute(
            array(
                $_GET['id'],
            )
        );

        $req2 = $bdd->prepare("DELETE FROM services_urgences WHERE id = ?") or die(print_r($bdd->errorInfo()));
        $req2->execute(
            array(
                $_GET['id'],
            )
        );
    }
?>