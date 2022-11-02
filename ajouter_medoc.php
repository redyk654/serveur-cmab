<?php
    header('Access-Control-Allow-Origin: *');

    // Mise à jour des stocks des médicaments vendus
    if(isset($_POST['medoc'])) {

        $medoc =  json_decode($_POST['medoc'], true);

        try {
            $bdd = new PDO('mysql:host=localhost;dbname=cma;charset=utf8', 'root', '');
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        $designation = $medoc['designation'];
        $grammage = $medoc['grammage'];
        $a_ajouter = $medoc['en_stock'];

        // On vérifie d'abord si le médicament est déjà dans la base
        $req = $bdd->query("SELECT * FROM medicaments WHERE (designation = '$designation' AND grammage = '$grammage')");

        $choix = 0;
        $en_stock = 0;
        while ($data = $req->fetch()) {
            if ($data['designation'] == $designation AND $data['grammage'] == $grammage) {
                $en_stock = $data['en_stock'];
                $choix++;
            }
        }

        // Si c'est le cas on met juste à jour son stock
        if ($choix == 1) {

            $nouveau_stock = intval($en_stock) + intval($a_ajouter);
            $req = $bdd->prepare("UPDATE medicaments SET en_stock = ? WHERE (designation = ? AND grammage = ?)");
            $req->execute(
                array(
                    $nouveau_stock,
                    $designation,
                    $grammage
                )
            );
        } else {

            // Sinon on l'insère dans la base
            $req = $bdd->prepare("INSERT INTO medicaments(id, designation, categorie, prix_unitaire, en_stock, min_rec, grammage) VALUES('', ?, ?, ?, ?, ?, ?)");
            $req->execute(
                array(
                    $medoc['designation'],
                    $medoc['categorie'],
                    $medoc['prix_unitaire'],
                    $medoc['en_stock'],
                    $medoc['min_rec'],
                    $medoc['grammage']
                )
            );
        }
        
    }
?>