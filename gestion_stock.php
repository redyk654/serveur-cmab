<?php
    header('Access-Control-Allow-Origin: *');

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
    }
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if(isset($_POST['produit']) AND isset($_GET['remarque'])) {
        $produit = json_decode($_POST['produit'], true);

        $req1 = $bdd->prepare("SELECT en_stock FROM medicaments WHERE id = ?");
        $req1->execute(
            array(
                $produit['id'],
            )
        );

        $data = $req1->fetch();

        if ($_GET['remarque'] == 'livraison') {
            // Mouvement d'approvisionnement
            $nv_stock = $data['en_stock'] + $produit['stock_ajoute'];

            $req2 = $bdd->prepare("INSERT INTO fiche_stock(id, designation, pu_vente, par, qte_entre, qte_dispo, date_heure, remarque) VALUES(:id, :designation, :pu_vente, :par, :qte_entre, :qte_dispo, NOW(), :remarque)");

            $req2->execute(
                array(
                    'id' => $produit['id'],
                    'designation' => $produit['designation'],
                    'pu_vente' => $produit['pu_vente'],
                    'par' => $_POST['par'],
                    'qte_entre' => $produit['stock_ajoute'],
                    'qte_dispo' => $nv_stock,
                    'remarque' => $_GET['remarque'],
                )
            );

            $req3 = $bdd->prepare("UPDATE medicaments SET en_stock = ?, designation = ?, categorie = ?, genre = ?, pu_vente= ?, min_rec = ?, date_peremption = ?, conditionnement = ? WHERE id = ?");

            $req3->execute(
                array(
                    $nv_stock,
                    $produit['designation'],
                    $produit['categorie'],
                    $produit['genre'],
                    $produit['pu_vente'],
                    $produit['min_rec'],
                    $produit['date_peremption'],
                    $produit['conditionnement'],
                    $produit['id'],
                )
            );
        }

    } else if (isset($_POST['id'])) {
        $req = $bdd->prepare("SELECT *, DATE_FORMAT(date_heure, '%d/%m/%Y %Hh%imin%ss') as date_heure FROM fiche_stock WHERE id = ? ORDER BY id_table DESC");

        $req->execute(
            array(
                $_POST['id'],
            )
        );

        $data = $req->fetchAll();

        echo json_encode($data);
    } else if (isset($_POST['designation']) AND  isset($_POST['id_prod']) AND isset($_POST['par']) AND isset($_POST['qte_dispo'])) {
        // Mouvement d'inventaire

        $req4 = $bdd->prepare("INSERT INTO fiche_stock(id, designation, pu_vente, par, qte_dispo, date_heure, remarque) VALUES(:id, :designation, :pu_vente, :par, :qte_dispo, NOW(), :remarque)");

        $req4->execute(
            array(
                'id' => $_POST['id_prod'],
                'designation' => $_POST['designation'],
                'pu_vente' => $_POST['pu_vente'],
                'par' => $_POST['par'],
                'qte_dispo' => $_POST['qte_dispo'],
                'remarque' => $_GET['rem'],
            )
        );

        $req5 = $bdd->prepare("UPDATE medicaments SET en_stock = ? WHERE id = ?");

        $req5->execute(
            array(
                $_POST['qte_dispo'],
                $_POST['id_prod'],
            )
        );

    }
?>