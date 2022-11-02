<?php 
    header('Access-Control-Allow-Origin: *');

    //Récupération des recettes à gérer

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    $debut = '2022-02-21' . ' 08:00:00';
    $fin = '2022-01-21' . ' 14:00:00';

    $req = $bdd->prepare('SELECT frais, id_facture FROM historique_services WHERE (date_heure BETWEEN :precedent AND :actuel) (SELECT * FROM medicaments)') or die(print_r($bdd->errorInfo()));
    $req->execute(
        array(
            'precedent' => $debut,
            'actuel' => $fin,
        )
    );
    
    $data = $req->fetchAll();
    echo json_encode($data);

    // declare(strict_types=1);

    // class Pont
    // {
    // public float $longueur = 0;
    // }

    // $pont = new Pont;
    // $pont->longueur = 263;

    // var_dump($pont);
    // echo 1==1;
?>