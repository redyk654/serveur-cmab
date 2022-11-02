<?php
    header('Access-Control-Allow-Origin: *');

    //Récupération des recettes à gérer

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['dateD']) AND isset($_POST['dateF']) AND $_POST['caissier'] AND isset($_GET['moment'])) {

        if ($_GET['moment'] == "nuit") {

            $debut = $_POST['dateD'] . ' 15:00:00';
            $fin = $_POST['dateF'] . ' 11:00:00';
            $reste = 0;

            $req = $bdd->prepare('SELECT SUM(prix_total) AS recette FROM facture_pharmacie
            WHERE caissier = :caissier AND reste_a_payer = :reste AND (date_heure BETWEEN :actuel AND :suivant)') or die(print_r($bdd->errorInfo()));
            $req->execute(
                array(
                    'caissier' => $_POST['caissier'],
                    'reste' => $reste,
                    'actuel' => $debut,
                    'suivant' => $fin,
                )
            );

        } else if ($_GET['moment'] == "jour") {

            $debut = $_POST['dateD'] . ' 06:00:00';
            $fin = $_POST['dateF'] . ' 18:00:00';
            $reste = 0;

            $req = $bdd->prepare('SELECT SUM(prix_total) AS recette FROM facture_pharmacie
            WHERE caissier = :caissier AND reste_a_payer = :reste AND (date_heure BETWEEN :actuel AND :suivant)') or die(print_r($bdd->errorInfo()));
            $req->execute(
                array(
                    'caissier' => $_POST['caissier'],
                    'reste' => $reste,
                    'actuel' => $debut,
                    'suivant' => $fin,
                )
            );
        }

        $data = $req->fetchAll();
        echo json_encode($data);
    }
?>