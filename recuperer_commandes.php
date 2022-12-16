<?php
    header('Access-Control-Allow-Origin: *');

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['id_commande'])) {

        $req = $bdd->prepare("SELECT * FROM approvisionnement WHERE id_commande = ?");
        $req->execute(
            array(
                $_POST['id_commande']
            )
        );

        $data = $req->fetchAll();
        echo json_encode($data);

    } else {
        // Récupération des informations des approvisionnements
        $req = $bdd->query("SELECT *, DATE_FORMAT(date_commande, '%d/%m/%Y %Hh%imin%ss') as date_commande FROM infos_approvisionnement ORDER BY id DESC");

        $data = $req->fetchAll();

        echo json_encode($data);
    }
?>