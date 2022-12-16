<?php
    header('Access-Control-Allow-Origin: *');

    //Récupération des recettes à gérer

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['dateD']) AND isset($_POST['dateF'])) {

        if (isset($_GET['vendeur'])) {

            $debut = $_POST['dateD'];
            $fin = $_POST['dateF'];
            $vendeur = $_GET['vendeur'];
            $statu = 'payé';
            
            $req = $bdd->prepare("SELECT *, DATE_FORMAT(date_vente, '%d/%m/%Y %Hh%imin%ss') as date_heure, SUM(quantite) AS quantite, SUM(prix_total) AS prix_total FROM historique WHERE (date_vente BETWEEN :precedent AND :actuel) AND nom_vendeur = :vendeur AND status_vente = :statu GROUP BY designation") or die(print_r($bdd->errorInfo()));
            $req->execute(
                array(
                    'precedent' => $debut,
                    'actuel' => $fin,
                    'vendeur' => $vendeur,
                    'statu' => $statu,
                )
            );

        } else {

            $debut = $_POST['dateD'];
            $fin = $_POST['dateF'];
            $statu = 'payé';
            
            $req = $bdd->prepare("SELECT *, DATE_FORMAT(date_vente, '%d/%m/%Y %Hh%imin%ss') as date_heure, SUM(quantite) AS quantite, SUM(prix_total) AS prix_total FROM historique WHERE date_vente BETWEEN :precedent AND :actuel AND status_vente = :statu GROUP BY designation") or die(print_r($bdd->errorInfo()));
            $req->execute(
                array(
                    'precedent' => $debut,
                    'actuel' => $fin,
                    'statu' => $statu,
                )
            );
        }

        $data = $req->fetchAll();
        echo json_encode($data);
    }
?>