<?php
    header('Access-Control-Allow-Origin: *');

    //Calcul de la recette en cour du caissier

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['nom']) and isset($_GET['service'])) {

        if ($_GET['service'] == 'nuit') {

            // Ici on ne récupère que la recette entre 15h et 10h30
            $actuel = date('Y-m-d');
            $precedent = date('Y-m-d', strtotime($actuel. ' -1 days'));
            $actuel .= ' 10:30:00';
            $precedent .= ' 15:00:00';
            
            $req = $bdd->prepare('SELECT SUM(prix) AS recette FROM historique_services
                                 WHERE caissier = :nom AND (date_heure BETWEEN :precedent AND :actuel)') or die(print_r($bdd->errorInfo()));
            $req->execute(
                array(
                    'nom' => $_POST['nom'],
                    'precedent' => $precedent,
                    'actuel' => $actuel
                )
            );
        } else if ($_GET['service'] == 'jour') {

            // Ici on récupère la recette entre 7h et 20h

            $actuel = date('Y-m-d');
            $suivant = $actuel . ' 20:00:00';
            $actuel .= ' 07:00:00';
            
            $req = $bdd->prepare('SELECT SUM(prix) AS recette FROM historique_services
                                 WHERE caissier = :nom AND (date_heure BETWEEN :actuel AND :suivant)') or die(print_r($bdd->errorInfo()));
            $req->execute(
                array(
                    'nom' => $_POST['nom'],
                    'actuel' => $actuel,
                    'suivant' => $suivant
                )
            );

        }

        $data = $req->fetch();

        echo json_encode($data);
    }
?>