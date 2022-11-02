<?php
    header('Access-Control-Allow-Origin: *');

    //Récupération des recettes à gérer

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['dateD']) AND isset($_POST['dateF']) AND isset($_POST['caissier'])) {

        $assurance = 'aucune';

        if ($_POST['assurance'] == "non") {
            $req = $bdd->prepare('SELECT SUM(prix) AS prix_total, designation, id FROM historique_services
            WHERE caissier = :caissier AND assurance = :assurance AND (date_heure BETWEEN :actuel AND :suivant) GROUP BY designation') or die(print_r($bdd->errorInfo()));
        } else {
            $req = $bdd->prepare('SELECT SUM(prix) AS prix_total, designation, id FROM historique_services
            WHERE caissier = :caissier AND assurance != :assurance AND (date_heure BETWEEN :actuel AND :suivant) GROUP BY designation') or die(print_r($bdd->errorInfo()));
        }
        
        $req->execute(
            array(
                'caissier' => $_POST['caissier'],
                'assurance' => $assurance,
                'actuel' => $_POST['dateD'],
                'suivant' => $_POST['dateF'],
            )
        );

        $data = $req->fetchAll();
        echo json_encode($data);
    }
?>