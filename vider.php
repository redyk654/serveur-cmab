<?php
    header('Access-Control-Allow-Origin: *');

    //Recupération des services

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    
    }
    if (isset($_GET['stock'])) {
        $req1 = $bdd->prepare("DELETE FROM medicaments WHERE en_stock = ?") or die(print_r($bdd->errorInfo()));
        $req1->execute(
            array(
                $_GET['stock'],
            )
        );
    }
?>