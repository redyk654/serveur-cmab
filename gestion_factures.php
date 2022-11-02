<?php
    header('Access-Control-Allow-Origin: *');

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['id']) AND isset($_POST['prix_total']) AND isset($_POST['remise']) AND isset($_POST['a_payer']) AND isset($_POST['montant_verse']) AND isset($_POST['relicat']) AND isset($_POST['reste_a_payer']) AND isset($_POST['caissier'])) {

        // Enregistrement de la facture
        $req = $bdd->prepare("INSERT INTO facture_caisse(id, caissier, patient, prix_total, frais, reduction, a_payer, montant_verse, relicat, reste_a_payer, date_heure, assurance, type_assurance, statu) 
        VALUES(:id, :caissier, :patient, :prix_total, :frais, :remise, :a_payer, :montant_verse, :relicat, :reste_a_payer, NOW(), :assurance, :type_assurance, :statu)");
    
        $req->execute(
            array(
                'id' => $_POST['id'],
                'caissier' => $_POST['caissier'],
                'patient' => $_POST['patient'],
                'prix_total' => $_POST['prix_total'],
                'frais' => $_POST['frais'],
                'remise' => $_POST['remise'],
                'a_payer' => $_POST['a_payer'],
                'montant_verse' => $_POST['montant_verse'],
                'relicat' => $_POST['relicat'],
                'reste_a_payer' => $_POST['reste_a_payer'],
                'assurance' => $_POST['assurance'],
                'type_assurance' => $_POST['type_assurance'],
                'statu' => $_POST['statu'],
            )
        );
    } else if (isset($_POST['id']) AND isset($_POST['montant_verse']) AND isset($_POST['reste_a_payer']) AND isset($_POST['relicat'])) {

        // Règlement d'une facture non réglé
        $req = $bdd->prepare("UPDATE facture_caisse SET montant_verse = ?, reste_a_payer = ?, relicat = ?  WHERE id = ?");
        $req->execute(
            array(
                $_POST['montant_verse'],
                $_POST['reste_a_payer'],
                $_POST['relicat'],
                $_POST['id'],
            )
        );


    } else if (isset($_GET['id'])) {

        // Récupération des détails d'une facture
        $req = $bdd->prepare("SELECT *, DATE_FORMAT(date_heure, '%d/%m/%Y %Hh%imin%ss') as date_heure FROM historique_services WHERE id_facture = ?");
        $req->execute(
            array(
                $_GET['id']
            )
        );

        $data = $req->fetchAll();

        echo json_encode($data);

    } else {
        // Récupération des factures


        if (isset($_GET['filtrer']) AND $_GET['filtrer'] == "oui") {
            if (isset($_GET['manquant'])) {

                $filtr = 0;
                $req = $bdd->prepare("SELECT SUM(reste_a_payer) AS manquant FROM facture_caisse WHERE reste_a_payer > ?");
                $req->execute(
                    array(
                        $filtr
                    )
                );
        
                $data = $req->fetchAll();
        
                echo json_encode($data);
            } else {

                $filtr = 0;
                $req = $bdd->prepare("SELECT *, DATE_FORMAT(date_heure, '%d/%m/%Y %Hh%imin%ss') as date_heure FROM facture_caisse WHERE reste_a_payer > ? ORDER BY id_fac DESC");
                $req->execute(
                    array(
                        $filtr
                    )
                );
        
                $data = $req->fetchAll();
        
                echo json_encode($data);
            }

        } else {
            $req = $bdd->prepare("SELECT *, DATE_FORMAT(date_heure, '%d/%m/%Y %Hh%imin%ss') as date_heure FROM facture_caisse ORDER BY id_fac DESC LIMIT 18");
            $req->execute();
    
            $data = $req->fetchAll();
    
            echo json_encode($data);
        }
    }
?>