<?php
require('XLSXReader.php');

class FFHB{
    /** parse the fichier xls */
    public static function importation($nomFichier){
        
        $xlsx = new XLSXReader($nomFichier);
        $sheets = $xlsx->getSheetNames();
        echo "feuille : " . $sheets[1];
        $data = $xlsx->getSheetData($sheets[1]);
        $taille = count($data);
        echo "taille : " . $taille;

        $champs = array{
            ':num_structure' => 'Num_structure',
            ':nom' => 'Nom',
            ':prenom' => 'Prenom',
            ':sexe' => 'Sexe',
            ':numro_licence' => 'Numero Licence',
            ':mention' => 'Mention',
            ':date_naissance' => 'Née le',
            ':email' => 'email',
            ':rue' => 'rue',
            ':cp' => 'CP',
            ':ville' => 'ville',
            ':tel_portable' => 'portable',
            ':tel_domicile' => 'domicile',
            ':tel_bureau' => 'bureau',
            ':tel_responsable_legal_1' => 'responsable_legal_1',
            ':tel_responsable_legal_2' => 'responsable_legal_2',
            ':num_appt' => 'num_appt',
            ':residence' => 'residence',
            ':lieu_dit' => 'lieu_dit',
            ':offreCom' => 'OffreCom'
        }

        
        for ($cpt_joueur = 1; $cpt_joueur < count($data)-1; $cpt_joueur ++){
            $joueur = array (
                ':num_structure' => $data[$cpt_joueur][0],
                ':nom' => $data[$cpt_joueur][1],
                ':prenom' => $data[$cpt_joueur][2],
                ':sexe' => $data[$cpt_joueur][3],
                ':numero_licence' => $data[$cpt_joueur][4],
                ':mention' => $data[$cpt_joueur][5],
                ':date_naissance' => $data[$cpt_joueur][6],
                ':email' => $data[$cpt_joueur][7],
                ':rue' => $data[$cpt_joueur][8],
                ':cp' => $data[$cpt_joueur][9],
                ':ville' => $data[$cpt_joueur][10],
                ':tel_portable' => $data[$cpt_joueur][11],
                ':tel_domicile' => $data[$cpt_joueur][12],
                ':tel_bureau' => $data[$cpt_joueur][13],
                ':tel_responsable_legal_1' => $data[$cpt_joueur][14],
                ':tel_responsable_legal_2' => $data[$cpt_joueur][15],
                ':num_appt' => $data[$cpt_joueur][16],
                ':residence' => $data[$cpt_joueur][17],
                ':lieu-dit' => $data[$cpt_joueur][18],
                ':offreCom' => $data[$cpt_joueur][19]
            );
           
            echo $joueur[':prenom'] . " - " . $joueur[':nom'];
        }
        var_dump($data[0]);
        echo "<br>";
        var_dump($data[1]);


        // on ferme
        $xlsx= null;

        // envoi du resultat
        // envoie de la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('admin/importation-selection.html.twig', 
                                    ['champs' => $champs,
                                    'joueurs' => $joueur);

    }
}

