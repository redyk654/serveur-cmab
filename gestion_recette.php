<?php
    header('Access-Control-Allow-Origin: *');

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['nom']) AND isset($_POST['montant'])) {

        // Enregistrement de la recette du jour d'un vendeur
        $req = $bdd->prepare("INSERT INTO recettes(nom, montant, date_versement) VALUES(:nom, :montant, NOW())");
    
        $req->execute(
            array(
                'nom' => $_POST['nom'],
                'montant' => $_POST['montant']
            )
        );
    } else if ($_GET['nom']) {
        // Récupération des recettes

        $req = $bdd->prepare("SELECT *, DATE_FORMAT(date_versement, '%d/%m/%Y') as date_versement FROM recettes WHERE nom = ?");
        $req->execute(
            array(
                $_GET['nom']
            )
        );

        $data = $req->fetchAll();

        echo json_encode($data);
    }
?>