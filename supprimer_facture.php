<?php
    header('Access-Control-Allow-Origin: *');

    //Suppression d'une facture de la base de donnée

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if(isset($_POST['id'])) {
        // Pour les services
        $req = $bdd->prepare("DELETE FROM facture_caisse WHERE id = ?");
        $req->execute(
            array(
                $_POST['id']
            )
        );
        $req2 = $bdd->prepare("DELETE FROM historique_services WHERE id_facture = ?");

        $req2->execute(
            array(
                $_POST['id'],
            )
        );

        $req3 = $bdd->prepare("DELETE FROM data_assurance WHERE id_facture = ?");

        $req3->execute(
            array(
                $_POST['id'],
            )
        );
    } else if(isset($_GET['id'])) {
        // Pour les produits
        // $req = $bdd->prepare("DELETE FROM facture_pharmacie WHERE id = ?");
        // $req->execute(
        //     array(
        //         $_GET['id']
        //     )
        // );
        // $req2 = $bdd->prepare("DELETE FROM historique WHERE id_facture = ?");

        // $req2->execute(
        //     array(
        //         $_GET['id'],
        //     )
        // );
    }
?>