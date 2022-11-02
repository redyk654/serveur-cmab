<?php
    header('Access-Control-Allow-Origin: *');

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['produit'])) {
           // Insertion d'un nouveau produit
        $produit = json_decode($_POST['produit'], true);

        $req = $bdd->prepare("INSERT INTO medicaments(code, designation, categorie, genre, pu_vente, pu_achat, conditionnement, min_rec, date_peremption, date_approv) 
                        VALUES(:code, :designation, :categorie, :genre, :pu_vente, :pu_achat, :conditionnement, :min_rec, :date_peremption, NOW())") or die(print_r($bdd->errorInfo()));
        $req->execute(
            array(
                'code' => $produit['code'],
                'designation' => $produit['designation'],
                'categorie' => $produit['categorie'],
                'genre' => $produit['genre'],
                'pu_vente' => $produit['pu_vente'],
                'pu_achat' => $produit['pu_achat'],
                'conditionnement' => $produit['conditionnement'],
                'min_rec' => $produit['min_rec'],
                'date_peremption' => $produit['date_peremption'],
            )
        );
    }

?>