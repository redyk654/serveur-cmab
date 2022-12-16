<?php
    header('Access-Control-Allow-Origin: *');

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['produit']) AND isset($_POST['id_commande'])) {

        // Insertion des produits commandés dans la table approvisionnement

        $produit = json_decode($_POST['produit'], true);
        
        $req = $bdd->prepare("INSERT INTO 
            approvisionnement(id, id_commande, code, designation, categorie, pu_vente, pu_achat, conditionnement, stock_commande, min_rec, date_peremption)
            VALUES('', :id_commande, :code, :designation, :categorie, :pu_vente, :pu_achat, :conditionnement, :stock_commande, :min_rec, :date_peremption)");
    
        $req->execute(
            array(
                'id_commande' => $_POST['id_commande'],
                'code' => $produit['code'],
                'designation' => $produit['designation'],
                'categorie' => $produit['categorie'],
                'pu_vente' => $produit['pu_vente'],
                'pu_achat' => $produit['pu_achat'],
                'conditionnement' => $produit['conditionnement'],
                'stock_commande' => $produit['stock_ajoute'],
                'min_rec' => $produit['min_rec'],
                'date_peremption' => $produit['date_peremption']
            )
        );
    } elseif (isset($_POST['id_commande']) AND isset($_POST['fournisseur']) AND isset($_POST['vendeur']) AND isset($_POST['montant'])) {

        // Insertion des informations relqtives à la commande en cours
        $req = $bdd->prepare("INSERT INTO infos_approvisionnement(id, id_commande, fournisseur, vendeur, montant, date_commande)
            VALUES('', :id_commande, :fournisseur, :vendeur, :montant, NOW())");

        $req->execute(
            array(
                'id_commande' => $_POST['id_commande'],
                'fournisseur' => $_POST['fournisseur'],
                'vendeur' => $_POST['vendeur'],
                'montant' => $_POST['montant']
            )
        );
    }
?>