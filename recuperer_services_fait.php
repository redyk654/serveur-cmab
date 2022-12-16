<?php
    header('Access-Control-Allow-Origin: *');

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    //Recupération de l'historique des services de la caisse

    if(isset($_GET['dateD']) AND isset($_GET['dateF'])) {

        if (isset($_GET['recette'])) {
            $req = $bdd->prepare("SELECT SUM(a_payer) AS recette FROM facture_caisse WHERE date_heure BETWEEN ? AND ?") or die(print_r($bdd->errorInfo()));
        
            $req->execute(
                array(
                    $_GET['dateD'],
                    $_GET['dateF']
                )
            );
        
            $data = $req->fetchAll();
        
            echo json_encode($data);
        } else {

            $req = $bdd->prepare("SELECT *, DATE_FORMAT(date_fait, '%d/%m/%Y') AS date_fait, DATE_FORMAT(heure_fait, '%Hh%imin%ss') AS heure_fait FROM historique_services WHERE date_heure BETWEEN ? AND ? ORDER BY id DESC") or die(print_r($bdd->errorInfo()));
        
            $req->execute(
                array(
                    $_GET['dateD'],
                    $_GET['dateF']
                )
            );
        
            $data = $req->fetchAll();
        
            echo json_encode($data);
        }

    }
?>