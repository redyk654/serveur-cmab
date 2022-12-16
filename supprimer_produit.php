<?php
    header('Access-Control-Allow-Origin: *');

    //Suppression d'un produit de la base de donnée

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if(isset($_POST['id'])) {

        $req = $bdd->prepare("DELETE FROM medicaments WHERE id=?");
        $req->execute(
            array(
                $_POST['id']
            )
        );
    }
?>