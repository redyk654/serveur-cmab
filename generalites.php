<?php
    header('Access-Control-Allow-Origin: *');

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_GET['code']) AND isset($_POST['caissier']) AND isset($_GET['moment'])) {

        if ($_GET['moment'] == "nuit") {    
            // Ici on récupère les détails des recettes des services entre 15h et 12h00
            $code = $_GET['code'].'%';
            $actuel = date('Y-m-d');
            $precedent = date('Y-m-d', strtotime($actuel. ' -1 days'));
            $actuel .= ' 12:00:00';
            $precedent .= ' 15:00:00';
            
            $req = $bdd->prepare('SELECT SUM(prix) AS prix, designation FROM historique_services
                                WHERE designation LIKE :code AND caissier = :caissier AND (date_heure BETWEEN :precedent AND :actuel) GROUP BY designation') or die(print_r($bdd->errorInfo()));
            $req->execute(
                array(
                    'code' => $code,
                    'caissier' => $_POST['caissier'],
                    'precedent' => $precedent,
                    'actuel' => $actuel,
                )
            );

        } else if ($_GET['moment'] == "jour") {    
            // Ici les détails des recettes des services entre 7h et 20h
            $code = $_GET['code'].'%';
            $actuel = date('Y-m-d');
            $suivant = $actuel . ' 20:00:00';
            $actuel .= ' 06:00:00';

            $req = $bdd->prepare('SELECT SUM(prix) AS prix, designation FROM historique_services
            WHERE designation LIKE :code AND caissier = :caissier AND (date_heure BETWEEN :actuel AND :suivant) GROUP BY designation') or die(print_r($bdd->errorInfo()));
            $req->execute(
                array(
                    'code' => $code,
                    'caissier' => $_POST['caissier'],
                    'actuel' => $actuel,
                    'suivant' => $suivant,
                )
            );
        }

        $data = $req->fetchAll();
        echo json_encode($data);
    }

?>