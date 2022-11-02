<?php 
    header('Access-Control-Allow-Origin: *');

    //Récupération des frais matériel

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['dateD']) AND isset($_POST['dateF']) AND isset($_POST['caissier'])) {

        $assurance = 'aucune';
        
        if ($_POST['assurance'] == "non") {
            $req = $bdd->prepare('SELECT SUM(frais) as frais FROM facture_caisse
            WHERE caissier = :caissier AND assurance = :assurance AND (date_heure BETWEEN :precedent AND :actuel)') or die(print_r($bdd->errorInfo()));
        } else {
            $req = $bdd->prepare('SELECT SUM(frais) as frais FROM facture_caisse
            WHERE caissier = :caissier AND assurance != :assurance AND (date_heure BETWEEN :precedent AND :actuel)') or die(print_r($bdd->errorInfo()));
        }

        $req->execute(
            array(
                'caissier' => $_POST['caissier'],
                'assurance' => $assurance,
                'precedent' => $_POST['dateD'],
                'actuel' => $_POST['dateF'],
            )
        );
        
        $data = $req->fetchAll();
        echo json_encode($data);
    }

?>