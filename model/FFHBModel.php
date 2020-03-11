<?php
require('XLSXReader.php');
require_once('Database.php');
require_once('UtilisateurBase.php');

class FFHB{
    /** parse the fichier xls */
    public static function importation($nomFichier){
        
        $xlsx = new XLSXReader($nomFichier);
        $sheets = $xlsx->getSheetNames();
        //echo "feuille : " . $sheets[1] ."<br>"; 
        $data = $xlsx->getSheetData($sheets[1]);

        // on ferme
        $xlsx= null;

        //nb_licencies = count($data)-1;
        
    /*    $champs = array (
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
        );
        */


        //echo "strftime : " .strftime("Y",$data[1][6]);

        //echo "count : " . count($data);
        
        $joueurs = null;
        
        for ($cpt_joueur = 1; $cpt_joueur < count($data); $cpt_joueur ++){
            //echo "date naissance : " . $data[$cpt_joueur][6] ."<br>";
            //echo "date : " . $data[$cpt_joueur][6] ."<br>";
            
            $date_array = (($data[$cpt_joueur][6])-25569) * 86400;
            //echo "date ok ? " . $date_ok."<br>";
            $annee= getdate($date_array)['year']; 
            $mois= getdate($date_array)['mon']; 
            $jour = getdate($date_array)['mday']; 
            
            $date_naissance = $annee . "-" . $mois . "-" . $jour;
            
            $joueur = array (
                'num_structure' => $data[$cpt_joueur][0],
                'nom' => $data[$cpt_joueur][1],
                'prenom' => $data[$cpt_joueur][2],
                'sexe' => $data[$cpt_joueur][3],
                'numero_licence' => $data[$cpt_joueur][4],
                'mention' => $data[$cpt_joueur][5],
                'date_naissance' => $date_naissance,
                'email' => $data[$cpt_joueur][7],
                'rue' => $data[$cpt_joueur][8],
                'cp' => $data[$cpt_joueur][9],
                'ville' => $data[$cpt_joueur][10],
                'tel_portable' => $data[$cpt_joueur][11],
                'tel_domicile' => $data[$cpt_joueur][12],
                'tel_bureau' => $data[$cpt_joueur][13],
                'tel_responsable_legal_1' => $data[$cpt_joueur][14],
                'tel_responsable_legal_2' => $data[$cpt_joueur][15],
                'num_appt' => $data[$cpt_joueur][16],
                'residence' => $data[$cpt_joueur][17],
                'lieu-dit' => $data[$cpt_joueur][18],
                'offreCom' => $data[$cpt_joueur][19]
            );
            $joueurs [$cpt_joueur] = $joueur;
            // echo $joueur[':prenom'] . " - " . $joueur[':nom'];

            $data = array(
                ':email'=>$data[$cpt_joueur][7],
            );

            var_dump($data);
            // requête à la base
            $db = new Database();
            $o_conn = $db->makeConnect();
            $ub = new UtilisateurBase();
            $ub_data = $ub->getUtilisateur($o_conn, $data);
            var_dump($ub_data);


        }        
       //$joueur = [];
       //var_dump($joueurs);
        return $joueurs;
    }
}