<?php
require_once 'model/Database.php';
require_once 'model/UtilisateurBase.php';
require_once 'model/Outils.php';

class UtilisateurController{
    /** affichage du formulaire de l'importation des données 
     * @param tabGET : $_GET
     * @param tabPost : $_POST
     * @param tabFile : $_FILES
     * @return render importation.html
    */

    public static function importation($tabGET,$tabPost, $tabFile){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('admin/import-formulaire.html.twig',
                                ['rub' => $tabGET['rub']]
                            );
    }

    /** traitement du fichier 
     * @param tabPost : $_POST
     * @param tabFile : $_FILES
    */
    public static function trtFichier($tabPost, $tabFile){
        $monAdresse = "intranet/lp-daoo/stage_hbck/public/docs/";
        if (isset($_FILES['dataFile'])) { 

            if (is_uploaded_file($_FILES['dataFile']['tmp_name'])) {
                $origin = $_FILES['dataFile']['tmp_name'];
                            
                // on récupère la date dans l'onglet
                $xlsx = new XLSXReader($_FILES['dataFile']['tmp_name']);
                $sheets = $xlsx->getSheetNames();
                $date_export = substr($sheets[1],0,10);
                
                // move du fichier
                $destination = $_SERVER['DOCUMENT_ROOT'] . $monAdresse .$date_export ."--data.xlsx";
                
                if (move_uploaded_file( $_FILES['dataFile']['tmp_name'], $destination))
                {
                    $fichier_joueurs = FFHBModel::importation($destination);
                    $nb_licencies_fichier = count($fichier_joueurs);

                    // on va chercher les infos de la Table Utilisateur de la base
                    $db = new Database();
                    $o_conn = $db->makeConnect();
                    $ub = new UtilisateurBase();

                    // les utilisateurs de la base
                    $ub_data = UtilisateurBase::getUtilisateurs($o_conn);
                
                    
                    // on recherche les élements insérer
                    $tabNew = FFHBModel::getElementsNouveaux($fichier_joueurs, $ub_data);
                    
                    // on recherches les élements déjà présents
                    $tabLicenciesAComparer = FFHBModel::getElementsPresents($fichier_joueurs, $ub_data);
                    $nb_a_comparer = count($tabLicenciesAComparer);

                    $comp_modifs = null;
                    
                    /** 
                     * pour chaque licencié de tabLicenciesAModifier (tableau des licenciés à modifier éventuellement) */                    
                    for ($cpt_pers = 0; $cpt_pers < $nb_a_comparer; $cpt_pers ++)
                    {
                        // on remplit la ligne à partir des infos du fichier
                        $comp_modifs[$cpt_pers] =
                            array('fichier' => $tabLicenciesAComparer[$cpt_pers])
                            ;
                        
                        // on remplit la ligne à partie des infos de la base pour la personne qui a le même mail
                        $tabEmail = array(
                                       ':email' => $tabLicenciesAComparer[$cpt_pers]['email']
                            );
                        $comp_modifs[$cpt_pers]['bdd'] = UtilisateurBase::getUtilisateur($o_conn, $tabEmail)[0];    
                        
                    }    
                    
                    //var_dump($comp_modifs);


                    // envoi du résultat à la vue
                    $loader = new \Twig\Loader\FilesystemLoader('view');
                    $twig = new \Twig\Environment($loader, [
                        'cache' => false,
                    ]);
                    echo $twig->render('admin/import-resultat-lecture.html.twig', 
                                                ["traitement" => "importation",
                                                "resultat" => "ok",
                                                "nb_licencies" => $nb_licencies_fichier,
                                                //"fichier_joueurs"=> $fichier_joueurs,
                                                "nouveaux"=>$tabNew,
                                                //"modifs"=>$tabModif,
                                                //ub_data"=>$ub_data,
                                                "destination"=>$destination,
                                                "comp_modifs" => $comp_modifs,
                                                "nb_a_comparer" => $nb_a_comparer,
                                                ]
                                    );                              
                }
            }
            else 
            {
                $loader = new \Twig\Loader\FilesystemLoader('view');
                $twig = new \Twig\Environment($loader, [
                                'cache' => false,
                            ]);

                echo $twig->render('admin/import-resultat-lecture.html.twig',
                        ["traitement"=> "importation",
                        'resultat' => false]
                    );
            }
        }
        else{
            $loader = new \Twig\Loader\FilesystemLoader('view');
                $twig = new \Twig\Environment($loader, [
                                'cache' => false,
                            ]);

                echo $twig->render('admin/import-pas-upload.html.twig');
        }
    }


    /** 
     * function qui récupère les nouveaux utilisateurs et les ajout en base
     * @param $tabPost : $_POST
     */
    public static function creationNouveauBase($tabPost){
        // on refait le fichier des licencies
        $destination= $tabPost['destination'];
        $fichier_licencies = FFHBModel::importation($destination);
        $nb_licencies = count($fichier_licencies);

        // on va chercher les infos de la Table Utilisateur de la base
        $db = new Database();
        $o_conn = $db->makeConnect();
        $ub = new UtilisateurBase();

        // on récupère les utilisateurs actuels
        $ub_data = $ub->getUtilisateurs($o_conn);

        // on recherche les élements insérer
        $tabNew = FFHBModel::getElementsNouveaux($fichier_licencies, $ub_data);
        //var_dump($tabNew);

        // on parcouers les élements et pour chaque élements, on procède à l'insertion
        for ($i = 0; $i<count($tabNew); $i++){
            // insertion
            //echo "insertion de element $i : " . $tabNew[$i]['nom']."<br>";
            $oCom = 0;
            if (strtolower($tabNew[$i]['offreCom']) == "oui")
            {
                $oCom = 1;
            }
            
            $data_entree = array(
                    ":num_structure" => $tabNew[$i]['num_structure'],
                    ":nom" => $tabNew[$i]['nom'],
                    ":prenom" => $tabNew[$i]['prenom'],
                    ":sexe" => $tabNew[$i]['sexe'],
                    ":numero_licence" => $tabNew[$i]['numero_licence'],
                    ":mention" => $tabNew[$i]['mention'],
                    ":date_naissance" => $tabNew[$i]['date_naissance'],
                    ":email" => $tabNew[$i]['email'],
                    ":adresse" => $tabNew[$i]['rue'],
                    ":cp" => strval($tabNew[$i]['cp']),
                    ":ville" => $tabNew[$i]['ville'],
                    ":lieu_dit" => $tabNew[$i]['lieu_dit'],
                    ":tel_portable" => $tabNew[$i]['tel_portable'],
                    ":tel_bureau" => $tabNew[$i]['tel_bureau'],
                    ":tel_resp_legal_1" => $tabNew[$i]['tel_responsable_legal_1'],
                    ":tel_resp_legal_2" => $tabNew[$i]['tel_responsable_legal_2'],
                    ":num_appt" => $tabNew[$i]['num_appt'],
                    ":residence" => $tabNew[$i]['residence'],
                    ":offrecom" => $oCom,
            );
            // insertion dans la base
            $ret_addUser = $ub->addUtilisateur($o_conn, $data_entree);
        }
        
        // on envoit la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                        'cache' => false,
                     ]);

        //var_dump($ret_addUser);
        if ($ret_addUser==true){
            echo $twig->render('admin/import-resultat-ajout-bdd.html.twig',
                                    ["traitement"=> "import-lot",
                                    'resultat' =>true]
                                );
        }
        else
        {
            echo $twig->render('admin/import-resultat-ajout-bdd.html.twig',
                                    ["traitement"=> "import-lot",
                                    'resultat' =>false]
                                );               
            
        } 
    }
}