<?php
    header('Access-Control-Allow-Origin: *');

    // Mise à jour du prix d'un produit

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if(isset($_POST['produit'])) {
        $produit = json_decode($_POST['produit'], true);

        $req = $bdd->prepare("UPDATE medicaments SET code = ?, designation = ?, classe = ?, categorie = ?, genre = ?, pu_vente= ?, pu_achat = ?, min_rec = ?, date_peremption = ?, conditionnement = ? WHERE id = ?");
        $req->execute(
            array(
                $produit['code'],
                $produit['designation'],
                $produit['classe'],
                $produit['categorie'],
                $produit['genre'],
                $produit['pu_vente'],
                $produit['pu_achat'],
                $produit['min_rec'],
                $produit['date_peremption'],
                $produit['conditionnement'],
                $produit['id'],
            )
        );
    }
?>