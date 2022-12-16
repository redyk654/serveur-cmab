<?php
    header('Access-Control-Allow-Origin: *');

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['id']) AND isset($_POST['prix_total']) AND isset($_POST['a_payer']) AND isset($_POST['montant_verse']) AND isset($_POST['relicat']) AND isset($_POST['reste_a_payer']) AND isset($_POST['vendeur'])) {

        // Enregistrement de la facture
        $req = $bdd->prepare("INSERT INTO facture_pharmacie(id, vendeur, patient, prix_total, a_payer, montant_verse, relicat, reste_a_payer, date_heure, assurance, type_assurance, statu) 
        VALUES(:id, :vendeur, :patient, :prix_total, :a_payer, :montant_verse, :relicat, :reste_a_payer, NOW(), :assurance, :type_assurance, :statu)");
    
        $req->execute(
            array(
                'id' => $_POST['id'],
                'vendeur' => $_POST['vendeur'],
                'patient' => $_POST['patient'],
                'prix_total' => $_POST['prix_total'],
                'a_payer' => $_POST['a_payer'],
                'montant_verse' => $_POST['montant_verse'],
                'relicat' => $_POST['relicat'],
                'reste_a_payer' => $_POST['reste_a_payer'],
                'assurance' => $_POST['assurance'],
                'type_assurance' => $_POST['type_assurance'],
                'statu' => $_POST['statu'],
            )
        );
    } else if (isset($_POST['id']) AND isset($_POST['montant_verse']) AND isset($_POST['reste_a_payer']) AND isset($_POST['relicat']) AND isset($_POST['caissier'])) {

        // Règlement d'une facture non réglé
        $req = $bdd->prepare("UPDATE facture_pharmacie SET caissier = ?, montant_verse = ?, reste_a_payer = ?, relicat = ? WHERE id = ?");
        $req->execute(
            array(
                $_POST['caissier'],
                $_POST['montant_verse'],
                $_POST['reste_a_payer'],
                $_POST['relicat'],
                $_POST['id'],
            )
        );

        $status = "payé";

        $req2 = $bdd->prepare("UPDATE historique SET status_vente = ? WHERE id_facture = ?");
        $req2->execute(
            array(
                $status,
                $_POST['id'],
            )
        );

    } else if (isset($_GET['id'])) {

        // Récupération des détails d'une facture
        $req = $bdd->prepare("SELECT *, DATE_FORMAT(date_vente, '%d/%m/%Y %Hh%imin%ss') as date_heure FROM historique WHERE id_facture = ?");
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
                $req = $bdd->prepare("SELECT SUM(reste_a_payer) AS manquant FROM facture_pharmacie WHERE reste_a_payer > ?");
                $req->execute(
                    array(
                        $filtr
                    )
                );
        
                $data = $req->fetchAll();
        
                echo json_encode($data);
            } else {

                $filtr = 0;
                $caissier='';
                $statu = "pending";
                $typ = 100;

                $req = $bdd->prepare("SELECT *, 
                                        DATE_FORMAT(date_heure, '%d/%m/%Y %Hh%imin%ss') as date_heure FROM facture_pharmacie
                                        WHERE reste_a_payer > ? OR caissier = ? 
                                        ORDER BY id_fac
                                        DESC LIMIT 17");
                $req->execute(
                    array(
                        $filtr,
                        $caissier
                    )
                );
        
                $data = $req->fetchAll();
        
                echo json_encode($data);
            }

        } else {
            $filtr = 0;
            $caissier='';

            $req = $bdd->prepare("SELECT *, DATE_FORMAT(date_heure, '%d/%m/%Y %Hh%imin%ss') as date_heure FROM facture_pharmacie WHERE reste_a_payer = ? AND caissier != ? ORDER BY id_fac DESC LIMIT 20");
            $req->execute(
                array(
                    $filtr,
                    $caissier
                )
            );
    
            $data = $req->fetchAll();
    
            echo json_encode($data);
        }
    }
?>