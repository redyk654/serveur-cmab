<?php
    header('Access-Control-Allow-Origin: *');

    // Mise à jour du prix d'un produit

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if(isset($_POST['designation']) AND isset($_POST['nvprix'])) {

        $req = $bdd->prepare("UPDATE medicaments SET pu_vente = ? WHERE designation = ?");
        $req->execute(
            array(
                $_POST['nvprix'],
                $_POST['designation']
            )
        );
    }
?>