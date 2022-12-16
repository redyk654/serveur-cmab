<?php
    header('Access-Control-Allow-Origin: *');

    //Suppression d'un vendeur

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if(isset($_GET['compte'])) {

        $req = $bdd->prepare("DELETE FROM utilisateurs WHERE nom_user = ?") or die(print_r(($bdd->errorInfo())));
        $req->execute(
            array(
                $_GET['compte']
            )
        );
    }
?>