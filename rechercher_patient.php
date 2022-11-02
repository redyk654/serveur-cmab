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

        $req = $bdd->prepare("SELECT * FROM patients WHERE nom LIKE ? ORDER BY nom LIMIT 15") or die(print_r($bdd->errorInfo()));
        $req->execute(
            array(
                $str
            )
        );
    
        $data = $req->fetchAll();
    
        echo json_encode($data);
    }

?>