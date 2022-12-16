<?php
    header('Access-Control-Allow-Origin: *');

    //Récupération des recettes à gérer

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['caissier']) AND isset($_POST['dateD']) AND isset($_POST['dateF'])) {

        if (isset($_GET['details'])) {
            $req = $bdd->prepare('SELECT SUM(prix) AS prix_total, COUNT(designation) AS nb, designation, categorie FROM historique_services
            WHERE caissier = :caissier AND (date_heure BETWEEN :precedent AND :actuel) GROUP BY designation') or die(print_r($bdd->errorInfo()));
            $req->execute(
                array(
                'caissier' => $_POST['caissier'],
                'precedent' => $_POST['dateD'],
                'actuel' => $_POST['dateF'],
                )
            );
        } else if (isset($_GET['frais'])) {

            $req = $bdd->prepare('SELECT SUM(frais) as frais FROM facture_caisse
            WHERE caissier = :caissier AND (date_heure BETWEEN :precedent AND :actuel)') or die(print_r($bdd->errorInfo()));
            $req->execute(
                array(
                    'caissier' => $_POST['caissier'],
                    'precedent' => $_POST['dateD'],
                    'actuel' => $_POST['dateF'],
                )
            );
        } else {
            // Récupération des recettes par catégorie
            
            $req = $bdd->prepare('SELECT SUM(prix) AS recette, categorie FROM historique_services
                                    WHERE caissier = :caissier AND (date_heure BETWEEN :precedent AND :actuel) GROUP BY categorie') or die(print_r($bdd->errorInfo()));
            $req->execute(
                array(
                    'caissier' => $_POST['caissier'],
                    'precedent' => $_POST['dateD'],
                    'actuel' => $_POST['dateF']
                )
            );    
        }
    
        $data = $req->fetchAll();
        echo json_encode($data);
    } else if (isset($_POST['id_recette']) AND isset($_POST['recette_total']) AND isset($_POST['montant_retire']) AND isset($_POST['recette_restante']) AND isset($_POST['caissier']) AND isset($_POST['regisseur'])) {
        // Insertion des recette calculés

        $req = $bdd->prepare("INSERT 
        INTO recettes_enregistres(id_recette, recette_total, montant_retire, recette_restante, caissier, regisseur, date_heure)
        VALUES(:id_recette, :recette_total, :montant_retire, :recette_restante, :caissier, :regisseur, NOW())") or die(print_r($bdd->errorInfo()));

        $req->execute(
            array(
                'id_recette' => $_POST['id_recette'],
                'recette_total' => $_POST['recette_total'],
                'montant_retire' => $_POST['montant_retire'],
                'recette_restante' => $_POST['recette_restante'],
                'caissier' => $_POST['caissier'],
                'regisseur' => $_POST['regisseur'],
            )
        );
    } else if (isset($_POST['id_recette']) AND isset($_POST['categorie']) AND isset($_POST['recette']) AND isset($_POST['pourcentage']) AND isset($_POST['recette_restante'])) {
        // Insertion des détails de la recette calculé

        $req = $bdd->prepare("INSERT INTO
        details_recette(id_recette, categorie, recette, pourcentage, recette_restante)
        VALUES(:id_recette, :categorie, :recette, :pourcentage, :recette_restante)") or die(print_r($bdd->errorInfo()));

        $req->execute(
            array(
                'id_recette' => $_POST['id_recette'],
                'categorie' => $_POST['categorie'],
                'recette' => $_POST['recette'],
                'pourcentage' => $_POST['pourcentage'],
                'recette_restante' => $_POST['recette_restante'],
            )
        );

    } else if (isset($_GET['recuperer'])) {
        // Recupération de la liste des recettes

        $req = $bdd->query("SELECT *, DATE_FORMAT(date_heure, '%d/%m/%Y %Hh%imin%ss') AS date_heure FROM recettes_enregistres ORDER BY id DESC") or die(print_r($bdd->errorInfo()));

        $data = $req->fetchAll();

        echo json_encode($data);
    } else if (isset($_GET['id_recette'])) {
        $req = $bdd->prepare("SELECT * FROM details_recette WHERE id_recette = ?") or die(print_r($bdd->errorInfo()));

        $req->execute(
            array(
                $_GET['id_recette'],
            )
        );

        $data = $req->fetchAll();
        echo json_encode($data);
    }
?>