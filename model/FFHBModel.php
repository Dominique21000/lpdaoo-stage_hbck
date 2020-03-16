<?php
require('XLSXReader.php');

class FFHBModel 
{
    /** parse the fichier xls */
    public static function importation($nomFichier){
        
        $xlsx = new XLSXReader($nomFichier);
        $sheets = $xlsx->getSheetNames();
        //echo "feuille : " . $sheets[1];
        $data = $xlsx->getSheetData($sheets[1]);
        // on ferme
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
                'date_naissance' => $data[$cpt_joueur][6],
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
            // echo $joueur[':prenom'] . " - " . $joueur[':nom'];
        }        
       
        //var_dump($joueurs);
        return $joueurs;
    }

    /** function getElementsnouveaux , dont le principe est :
     * - parcours d'un fichier de licenciés
     * - pour chaque licenciés, on vérifie s'il est présent dans la base
     * => on crée un tableau des licencies qui ne sont pas dans la base 
     */
    public static function getElementsNouveaux($licencies, $utilisateur){
        $tabNouv = null;
        $cptNouv = 0;
        /* on part à 1 pour le fichier des licenciés parce que 
            la première ligne est une ligne d'en-tête */ 
        //var_dump($licencies);
        for($cptLic = 1; $cptLic < count($licencies)+1 ; $cptLic++){
            // parcours du tableau de licencié
            $present = false;
            //echo "cptlic" .$cptLic . "<br>";
            for ($cptUtil = 0; $cptUtil < count($utilisateur); $cptUtil++){
                // parcours des utilisateurs
                //echo "licencies - email : " . $licencies[$cptLic]["email"] ."<br>";
                //echo "utilisateur - email : " . $utilisateur[$cptUtil][7] ." <br>";
                
                if ($licencies[$cptLic]['email'] == $utilisateur[$cptUtil]['uti_email']){
                    // ça matche
                    //echo "present<br>";
                    $present = true;      
                }
                else{
                    // nouveau
                    //echo "pas présent<br>";
                }
                //echo "utilisateur suivant<br>";
            }
            
            // on regarde si ça a matché
            if ($present == false){
                //echo "<b>pas présent => nouveau => on ajoute</b><br>";
                $tabNouv[$cptNouv] = $licencies[$cptLic];
                $cptNouv +=1;
            }
        }
        //var_dump($tabNouv);
        return $tabNouv;
        
    }    

    /** function getElementsPresents le principe est :
     * - parcours d'un fichier de licenciés
     * - pour chaque licenciés, on vérifie s'il est présent dans la base
     * => on crée un tableau des licencies qui sont déjàs présent dans la base.
     */
    public static function getElementsPresents($licencies, $utilisateur){
        $tabExiste = null;
        $cptExiste = 0;
        /* on part à 1 pour le fichier des licenciés parce que 
            la première ligne est une ligne d'en-tête */ 
        //var_dump($licencies);
        for($cptLic = 1; $cptLic < count($licencies)+1 ; $cptLic++){
            // parcours du tableau de licencié
            $present = false;
            //echo "cptlic" .$cptLic . "<br>";
            for ($cptUtil = 0; $cptUtil < count($utilisateur); $cptUtil++){
                // parcours des utilisateurs
                //echo "licencies - email : " . $licencies[$cptLic]["email"] ."<br>";
                //echo "utilisateur - email : " . $utilisateur[$cptUtil][7] ." <br>";
                
                if ($licencies[$cptLic]['email'] == $utilisateur[$cptUtil]['uti_email']){
                    // ça matche
                    //echo "present<br>";
                    $present = true;      
                }
                else{
                    // nouveau
                    //echo "pas présent<br>";
                }
                //echo "utilisateur suivant<br>";
            }
            
            // on regarde si ça a matché
            if ($present == true){
                //echo "<b>présent => à mettre à jour => on ajoute</b><br>";
                $tabExiste[$cptExiste] = $licencies[$cptLic];
                $cptExiste +=1;
            }
            
        }
        //var_dump($tabExiste);
        return $tabExiste;        
    }    
}