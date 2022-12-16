<?php
    header('Access-Control-Allow-Origin: *');

    // Enregistrement d'un nouveau caissier

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if(isset($_POST['nom']) AND isset($_POST['mdp']) AND isset($_POST['role'])) {

        $mdp_hash = password_hash($_POST['mdp'], PASSWORD_DEFAULT); // Hashage du mot de passe
        
        $req = $bdd->prepare("INSERT INTO utilisateurs_caisse(nom_user, mdpasse, rol) VALUES(:nom, :mdp, :rol)");

        $req->execute(
            array(
                'nom' => $_POST['nom'],
                'mdp' => $mdp_hash,
                'rol' => $_POST['role']
            )
        );
    }
?>