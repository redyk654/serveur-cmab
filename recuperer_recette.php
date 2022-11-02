<?php 
    header('Access-Control-Allow-Origin: *');

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['dateD']) AND isset($_POST['dateF'])) {
    
        $req = $bdd->prepare('SELECT * FROM facture_caisse WHERE (date_heure BETWEEN :precedent AND :actuel)') or die(print_r($bdd->errorInfo()));
        $req->execute(
            array(
                'precedent' => $_POST['dateD'],
                'actuel' => $_POST['dateF'],
            )
        );
        
        $data = $req->fetchAll();
        echo json_encode($data);
    }

?>