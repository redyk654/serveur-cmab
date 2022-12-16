<?php
    header('Access-Control-Allow-Origin: *');

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    // Connexion de l'utilisateur
    $message = null;
    if (isset($_POST['nom']) AND isset($_POST['mdp'])) {

        $req = $bdd->prepare("SELECT * FROM utilisateurs_caisse WHERE nom_user = ?");
        $req->execute(
            array(
                $_POST['nom']
            )
        );

        $data = $req->fetch();

        if($data) {
            if (password_verify($_POST['mdp'], $data['mdpasse'])) {
                
            } else {
                $message = "identifiant ou mot de passe incorrect";
            }
        } else {
            $message = "identifiant ou mot de passe incorrect";
        }

        if ($message) {
            echo $message;
        } else {
            echo json_encode($data);
        }
    }
?>