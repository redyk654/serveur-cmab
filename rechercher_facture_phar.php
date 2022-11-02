<?php
    header('Access-Control-Allow-Origin: *');

    //Recupération des comptes pour verification lors de la connexion au service caisse

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_GET['str'])) {

        $str = $_GET['str'] . '%';
        $filtr = 0;
        $statu = "pending";
        $caissier = '';
        $typ = 100;

        if (isset($_GET['caissier'])) {
            $req = $bdd->prepare("SELECT *, DATE_FORMAT(date_heure, '%d/%m/%Y %Hh%imin%ss') as date_heure FROM facture_pharmacie WHERE (reste_a_payer > ? OR (caissier = ? AND type_assurance = ?)) AND patient LIKE ? ORDER BY id_fac DESC");
            $req->execute(
                array(
                    $filtr,
                    $caissier,
                    $typ,
                    $str,
                )
            );
        } else {
            $req = $bdd->prepare("SELECT *, DATE_FORMAT(date_heure, '%d/%m/%Y %Hh%imin%ss') as date_heure FROM facture_pharmacie WHERE patient LIKE ? AND reste_a_payer = ? ORDER BY id_fac DESC") or die(print_r($bdd->errorInfo()));
            $req->execute(
                array(
                    $str,
                    $filtr
                )
            );
        }
    
        $data = $req->fetchAll();
    
        echo json_encode($data);
    }

?>