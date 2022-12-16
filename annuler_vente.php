<?php
    header('Access-Control-Allow-Origin: *');

    //Annulation d'une facture

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if(isset($_POST['id_prod']) AND isset($_POST['qte'])) {
        // Remise du stock sorti
        $req = $bdd->prepare("SELECT en_stock FROM medicaments WHERE id = ?");
        $req->execute(
            array(
                $_POST['id_prod'],
            )
        );

        $data = $req->fetch();
        $nv_stock = $data['en_stock'] + $_POST['qte'];

        $req2 = $bdd->prepare("UPDATE medicaments SET en_stock = ? WHERE id = ?");

        $req2->execute(
            array(
                $nv_stock,
                $_POST['id_prod']
            )
        );
    }
?>