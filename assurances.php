<?php
    header('Access-Control-Allow-Origin: *');

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    // Connexion de l'utilisateur
    $message = null;
    if (isset($_GET['liste'])) {

        $req = $bdd->query("SELECT * FROM assurances");

        $data = $req->fetchAll();

        echo json_encode($data);
    } else if (isset($_POST['designation'])) {
        $req = $bdd->prepare("INSERT INTO assurances(designation) VALUES(?)");

        $req->execute(
            array(
                $_POST['designation'],
            )
        );
    } else if (isset($_POST['supprime'])) {
        $req = $bdd->prepare("DELETE FROM assurances WHERE designation=?");

        $req->execute(
            array(
                $_POST['supprime'],
            )
        );
    }
?>