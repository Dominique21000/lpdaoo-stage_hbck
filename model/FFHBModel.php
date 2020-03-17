<?php
require('XLSXReader.php');

class FFHBModel 
{
    /** parse the fichier xls */
    public static function importation($nomFichier){
        
        $xlsx = new XLSXReader($nomFichier);
        $sheets = $xlsx->getSheetNames();
        $data = $xlsx->getSheetData($sheets[1]);
        $xlsx= null;
        $joueurs = null;
        
        for ($cpt_joueur = 1; $cpt_joueur < count($data); $cpt_joueur ++){
            $date_array = (($data[$cpt_joueur][6])-25569) * 86400;
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
                'lieu_dit' => $data[$cpt_joueur][18],
                'offreCom' => $data[$cpt_joueur][19]
            );
            $joueurs [$cpt_joueur] = $joueur;
        }        
       
        //var_dump($joueurs);
        return $joueurs;
    }

    /** function getElementsnouveaux , dont le principe est :
     * @param licencies : fichier de licenciés à parcourir
     * @param utilisteurs : liste des utilisateurs dans laquel on verifie la présente des licencies
     * @return tabNouv : tableau des nouveaux licenciés
     */
    public static function getElementsNouveaux($licencies, $utilisateurs){
        // var_dump($utilisateurs); -> ok
        //var_dump($licencies); -> ok
        $tabNouv = null;
        $cptNouv = 0;
        /*  on part à 1 pour le fichier des licenciés parce que 
            la première ligne est une ligne d'en-tête */ 
        for($cptLic = 1; $cptLic < count($licencies)+1 ; $cptLic++){
            // parcours du tableau de licencié
            $present = false;
            for ($cptUtil = 0; $cptUtil < count($utilisateurs); $cptUtil++){
                if ($licencies[$cptLic]['email'] == $utilisateurs[$cptUtil]['uti_email']){
                    //echo "present";
                    $present = true;      
                }
            }
            //echo "<br>";
            
            // on regarde si ça a matché
            if ($present == false){
                //echo "false => on ajoute";
                $tabNouv[$cptNouv] = $licencies[$cptLic];
                $cptNouv +=1;
            }
            //echo "<br>";
        }
        //var_dump($tabNouv);
        return $tabNouv;
        
    }    

    /** function getElementsPresents le principe est :
    * @param licencies : fichier de licenciés à parcourir
     * @param utilisteurs : liste des utilisateurs dans laquel on verifie la présente des licencies
     * @return tabModif : tableau des licenciés à mettre à jour
     */
    public static function getElementsPresents($licencies, $utilisateurs){
        $tabExiste = null;
        $cptExiste = 0;
        /* on part à 1 pour le fichier des licenciés parce que 
            la première ligne est une ligne d'en-tête */ 
        for($cptLic = 1; $cptLic < count($licencies)+1 ; $cptLic++){
            $present = false;
            for ($cptUtil = 0; $cptUtil < count($utilisateurs); $cptUtil++){
                if ($licencies[$cptLic]['email'] == $utilisateurs[$cptUtil]['uti_email']){
                    $present = true;      
                }
            }
            
            // on regarde si ça a matché
            if ($present == true){
                $tabExiste[$cptExiste] = $licencies[$cptLic];
                $cptExiste +=1;
            }
        }
        return $tabExiste;        
    }    
}