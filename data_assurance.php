<?php
    header('Access-Control-Allow-Origin: *');

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    // Sauvegarde des données des personnes assurés
    if(isset($_POST['designation']) AND isset($_POST['prix_total']) AND isset($_POST['id_facture']) AND isset($_POST['patient']) AND isset($_POST['categorie']) AND isset($_POST['quantite'])) {
        $req = $bdd->prepare("INSERT INTO 
                data_assurance(id_facture, patient, designation, qte, prix, categorie, date_heure)
                VALUES(?, ?, ?, ?, ?, ?, NOW())
                ") or die(print_r($bdd->errorInfo()));
        $req->execute(
            array(
                $_POST['id_facture'],
                $_POST['patient'],
                $_POST['designation'],
                $_POST['quantite'],
                $_POST['prix_total'],
                $_POST['categorie'],
            )
        );
    }
?>