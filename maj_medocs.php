<?php
    header('Access-Control-Allow-Origin: *');

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=cmab;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if(isset($_POST['produit']) AND $_POST['caissier']) {
        // Mise à jour des stocks des médicaments vendus
        $produit = json_decode($_POST['produit'], true);

        $req = $bdd->prepare("SELECT en_stock FROM medicaments WHERE id = ?");
        $req->execute(
            array(
                $produit['id_prod'],
            )
        );

        $data = $req->fetch();
        
        if($data['en_stock'] > 0) {

            $nv_stock = $data['en_stock'] - $produit['quantite'];
    
            $req2 = $bdd->prepare("UPDATE medicaments SET en_stock = ? WHERE id = ?");
    
            $req2->execute(
                array(
                    $nv_stock,
                    $produit['id_prod'],
                )
            );
            
            // Mise à jour de la fiche de stock
            $remarque = "sortie";
            $pu_vente = $produit['prix_total'] / $produit['quantite'];
            $req3 = $bdd->prepare("INSERT INTO 
                                    fiche_stock(id, designation, pu_vente, par, qte_sortie, qte_dispo, date_heure, remarque) 
                                    VALUES(:id, :designation, :pu_vente, :par, :qte_sortie, :qte_dispo, NOW(), :remarque)"
                                    );
    
            $req3->execute(
                array(
                    'id' => $produit['id_prod'],
                    'designation' => $produit['designation'],
                    'pu_vente' => $pu_vente,
                    'par' => $produit['nom_vendeur'],
                    'qte_sortie' => $produit['quantite'],
                    'qte_dispo' => $nv_stock,
                    'remarque' => $remarque,
                )
            );

            $req4 = $bdd->prepare("UPDATE historique SET caissier = ? WHERE id_facture = ?");
    
            $req4->execute(
                array(
                    $_POST['caissier'],
                    $produit['id_facture'],
                )
            );
            
        }

    }
    // elseif (isset($_POST['produit'])) {
    //     $produit = json_decode($_POST['produit'], true);
        
    //     $req = $bdd->prepare("INSERT INTO 
    //             medicaments(id, code, designation, categorie, pu_vente, pu_achat, conditionnement, en_stock, min_rec, date_peremption, date_approv)
    //             VALUES('', :code, :designation, :categorie, :pu_vente, :pu_achat, :conditionnement, :en_stock, :min_rec, :date_peremption, NOW())");
        
    //     $req->execute(
    //         array(
    //             'code' => $produit['code'],
    //             'designation' => $produit['designation'],
    //             'categorie' => $produit['categorie'],
    //             'pu_vente' => $produit['pu_vente'],
    //             'pu_achat' => $produit['pu_achat'],
    //             'conditionnement' => $produit['conditionnement'],
    //             'en_stock' => $produit['stock_ajoute'],
    //             'min_rec' => $produit['min_rec'],
    //             'date_peremption' => $produit['date_peremption']
    //         )
    //     );
    // }
?>