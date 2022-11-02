<?php 
    header('Access-Control-Allow-Origin: *');

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['dateD']) AND isset($_POST['dateF']) AND isset($_POST['caissier']) AND isset($_GET['moment'])) {

        $debut = $_POST['dateD'] . ' 08:00:00';
        $fin = '2022-01-21' . ' 14:00:00';
    
        $req = $bdd->prepare('SELECT frais, id_facture FROM historique_services WHERE (date_heure BETWEEN :precedent AND :actuel) (SELECT * FROM medicaments)') or die(print_r($bdd->errorInfo()));
        $req->execute(
            array(
                'precedent' => $debut,
                'actuel' => $fin,
            )
        );
        
        $data = $req->fetchAll();
        echo json_encode($data);
    }
?>