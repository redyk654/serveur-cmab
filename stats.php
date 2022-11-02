<?php
    header('Access-Control-Allow-Origin: *');

    //Récupération des recettes à gérer

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['mois'])) {

        $mois = $_POST['mois'].'%';
        
        $req = $bdd->prepare("SELECT designation, SUM(qte_entre) as qte_in, SUM(qte_sortie) as qte_out FROM fiche_stock WHERE date_heure LIKE ? GROUP BY designation") or die(print_r($bdd->errorInfo()));
        $req->execute(
            array(
                $mois,
            )
        );

        $data = $req->fetchAll();
        echo json_encode($data);
    }
?>