<?php
    header('Access-Control-Allow-Origin: *');

    // Modification du mot de passe d'un utilisateur

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    $message = null;
    if(isset($_POST['nom']) AND isset($_POST['ancien']) AND isset($_POST['nouveau'])) {

        $req = $bdd->prepare("SELECT * FROM utilisateurs WHERE nom_user = ?");
        $req->execute(
            array(
                $_POST['nom']
            )
        );

        $data = $req->fetch();

        if($data) {
            if (password_verify($_POST['ancien'], $data['mdpasse'])) {

                $mdp_hash = password_hash($_POST['nouveau'], PASSWORD_DEFAULT); // Hashage du mot de passe
        
                $req2 = $bdd->prepare("UPDATE utilisateurs SET mdpasse = ? WHERE nom_user = ?");
        
                $req2->execute(
                    array(
                        $mdp_hash,
                        $_POST['nom'],
                    )
                );
                echo 'ok';
            } else {
                $message = "L'ancien mot de passe ne corresppond pas";
            }
        } else {
            $message = "L'ancien mot de passe  corresppond pas";
        }

        echo $message;
    }
?>