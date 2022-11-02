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
        $typ = 100;

        $req = $bdd->prepare("SELECT *, DATE_FORMAT(date_heure, '%d/%m/%Y %Hh%imin%ss') as date_heure FROM facture_caisse WHERE patient LIKE ? AND reste_a_payer = ? ORDER BY id_fac DESC") or die(print_r($bdd->errorInfo()));
        $req->execute(
            array(
                $str,
                $filtr
            )
        );
    
        $data = $req->fetchAll();
    
        echo json_encode($data);
    }

?>