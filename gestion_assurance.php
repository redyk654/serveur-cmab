<?php
    header('Access-Control-Allow-Origin: *');

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    // Recupération des client assurés
    if(isset($_POST['date_min']) AND isset($_POST['date_max']) AND isset($_POST['assurance']) AND isset($_GET['categorie'])) {
        if ($_GET['categorie'] == 'pharmacie') {

            $date_min = $_POST['date_min'].' 00:00:00';
            $date_max = $_POST['date_max'].' 23:59:59';
            $statu = 'pending';
    
            $req = $bdd->prepare("SELECT id_fac, id, patient, prix_total, assurance, type_assurance, date_heure FROM
                    facture_pharmacie WHERE statu = :statu AND assurance = :assurance AND (date_heure BETWEEN :date_min AND :date_max) ORDER BY date_heure") or die(print_r($bdd->errorInfo()));
            $req->execute(
                array(
                    'statu' => $statu,
                    'assurance' => $_POST['assurance'],
                    'date_min' => $date_min,
                    'date_max' => $date_max,
                )
            );
        } else {
            $date_min = $_POST['date_min'].' 00:00:00';
            $date_max = $_POST['date_max'].' 23:59:59';
            $statu = 'pending';
    
            $req = $bdd->prepare("SELECT id_fac, id, patient, prix_total, frais, assurance, type_assurance, date_heure FROM
                    facture_caisse WHERE statu = :statu AND assurance = :assurance AND (date_heure BETWEEN :date_min AND :date_max) ORDER BY date_heure") or die(print_r($bdd->errorInfo()));
            $req->execute(
                array(
                    'statu' => $statu,
                    'assurance' => $_POST['assurance'],
                    'date_min' => $date_min,
                    'date_max' => $date_max,
                )
            );
        }

        $data = $req->fetchAll();
        echo json_encode($data);

    } else if (isset($_GET['facture'])) {
        $req = $bdd->prepare("SELECT * FROM data_assurance WHERE id_facture = :facture") or die(print_r($bdd->errorInfo()));
        $req->execute(
            array(
                'facture' => $_GET['facture'],
            )
        );

        $data = $req->fetchAll();
        echo json_encode($data);
    } else if (isset($_POST['id_fac']) AND isset($_POST['categorie'])) {
        if ($_POST['categorie'] == "pharmacie") {
            $req = $bdd->prepare("UPDATE facture_pharmacie SET statu = ? WHERE id = ?");
    
            $req->execute(
                array(
                    "done",
                    $_POST['id_fac'],
                )
            );
        } else {

            $req = $bdd->prepare("UPDATE facture_caisse SET statu = ? WHERE id = ?");
    
            $req->execute(
                array(
                    "done",
                    $_POST['id_fac'],
                )
            );
        }
    } else if (isset($_POST['id_facture']) AND isset($_POST['nom']) AND isset($_POST['assurance']) AND isset($_POST['assurance_type']) AND isset($_POST['periode']) AND isset($_POST['total']) AND isset($_POST['reste'])) {
            // Enregistrement de la facture
        $req = $bdd->prepare("INSERT INTO facture_assurance(id_facture, nom, assurance, assurance_type, periode, total, reste, date_heure) 
        VALUES(:id_facture, :nom, :assurance, :assurance_type, :periode, :total, :reste, NOW())");
    
        $req->execute(
            array(
                'id_facture' => $_POST['id_facture'],
                'nom' => $_POST['nom'],
                'assurance' => $_POST['assurance'],
                'assurance_type' => $_POST['assurance_type'],
                'periode' => $_POST['periode'],
                'total' => $_POST['total'],
                'reste' => $_POST['reste'],
            )
        );
    } else if (isset($_POST['id_facture']) AND isset($_POST['id_general'])) {
        $req = $bdd->prepare("INSERT INTO id_facture_assure(id_general, id_facture) 
        VALUES(?, ?)");
    
        $req->execute(
            array(
                $_POST['id_general'],
                $_POST['id_facture'],
            )
        );
    } else if (isset($_GET['facture_fait'])) {
        $req = $bdd->query("SELECT * FROM facture_assurance ORDER BY id DESC") or die(print_r($bdd->errorInfo()));

        $data = $req->fetchAll();
        echo json_encode($data);

    } else if (isset($_GET['id_general'])) {
        $req = $bdd->prepare("SELECT * FROM id_facture_assure WHERE id_general = :id_general") or die(print_r($bdd->errorInfo()));
        $req->execute(
            array(
                'id_general' => $_GET['id_general'],
            )
        );

        $data = $req->fetchAll();
        echo json_encode($data);
    }
?>