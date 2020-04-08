<?php
require_once 'model/Database.php';
require_once 'model/UtilisateurBase.php';
require_once 'model/Outils.php';
require_once 'model/LicencieBase.php';

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

    public static function majUtilisateur($tabPost){
        echo "dans maj utilisateur";
        //var_dump($tabPost);

         // on va chercher les infos de la Table Utilisateur de la base
         $db = new Database();
         $o_conn = $db->makeConnect();

        // on va chercher les max des utilisateurs
        $uti_id_maxi = UtilisateurBase::getMaxiUtilisateur($o_conn);
        echo "<br/>maxi uti = " . $uti_id_maxi;
        echo "<br>";

        // on balaye chaque utilisateur
        for ($cpt_utilisateur=1 ; $cpt_utilisateur <= $uti_id_maxi; $cpt_utilisateur++)
        {
            //echo $cpt_utilisateur. " - " ;
            if (isset($tabPost[$cpt_utilisateur."_structure_maj_ok"])) {
                echo "struc à maj à ". $tabPost[$cpt_utilisateur."_structure_maj_ok"]."<br>";
                $data = array (
                    ':num_structure' => $tabPost[$cpt_utilisateur."_structure_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateStructure($o_conn, $data);

            }
        
            if (isset($tabPost[$cpt_utilisateur."_prenom_maj_ok"])){
                echo "prenom à maj à ". $tabPost[$cpt_utilisateur."_prenom_maj_ok"]."<br>";
                $data = array (
                    ':prenom' => $tabPost[$cpt_utilisateur."_prenom_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updatePrenom($o_conn, $data);
            }
                

            if (isset($tabPost[$cpt_utilisateur."_nom_maj_ok"]))
            {
                echo "nom à maj à ". $tabPost[$cpt_utilisateur."_nom_maj_ok"]."<br>";
                $data = array (
                    ':nom' => $tabPost[$cpt_utilisateur."_nom_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateNom($o_conn, $data);
            }
                

            if (isset($tabPost[$cpt_utilisateur."_sexe_maj_ok"]))
            {   
                echo "sexe à maj à ". $tabPost[$cpt_utilisateur."_sexe_maj_ok"]."<br>";
                $data = array (
                    ':sexe' => $tabPost[$cpt_utilisateur."_sexe_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateSexe($o_conn, $data);
            }
                

            if (isset($tabPost[$cpt_utilisateur."_licence_maj_ok"])){
                echo "licence à maj à ". $tabPost[$cpt_utilisateur."_licence_maj_ok"]."<br>";
                $data = array (
                    ':numero_licence' => $tabPost[$cpt_utilisateur."_licence_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateNumeroLicence($o_conn, $data);
            }
                
        
            if (isset($tabPost[$cpt_utilisateur."_mention_maj_ok"])){
                echo "mention à maj à ". $tabPost[$cpt_utilisateur."_mention_maj_ok"]."<br>";
                $data = array (
                    ':mention' => $tabPost[$cpt_utilisateur."_mention_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateMention($o_conn, $data);
            }
                

            if (isset($tabPost[$cpt_utilisateur."_date_maj_ok"])){
                echo "date à maj à ". $tabPost[$cpt_utilisateur."_date_maj_ok"]."<br>";
                $data = array (
                    ':date_naissance' => $tabPost[$cpt_utilisateur."_date_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateDateNaissance($o_conn, $data);
            }
                

            if (isset($tabPost[$cpt_utilisateur."_email_maj_ok"]))  {
                echo "mail à maj à ". $tabPost[$cpt_utilisateur."_email_maj_ok"]."<br>";
                $data = array (
                    ':email' => $tabPost[$cpt_utilisateur."_email_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateEmail($o_conn, $data);
            }
                

            if (isset($tabPost[$cpt_utilisateur."_rue_maj_ok"])){
                echo "rue à maj à ". $tabPost[$cpt_utilisateur."_rue_maj_ok"]."<br>";
                $data = array (
                    ':adresse' => $tabPost[$cpt_utilisateur."_rue_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateRue($o_conn, $data);
            }

            if (isset($tabPost[$cpt_utilisateur."_cp_maj_ok"])) {
                echo "cp à maj à ". $tabPost[$cpt_utilisateur."_cp_maj_ok"]."<br>";
                $data = array (
                    ':cp' => $tabPost[$cpt_utilisateur."_cp_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateCp($o_conn, $data);
            }
            
            if (isset($tabPost[$cpt_utilisateur."_ville_maj_ok"])){
                echo "ville à maj à ". $tabPost[$cpt_utilisateur."_ville_maj_ok"]."<br>";
                $data = array (
                    ':ville' => $tabPost[$cpt_utilisateur."_ville_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateVille($o_conn, $data);
            }
                
            if (isset($tabPost[$cpt_utilisateur."_portable_maj_ok"])){
                echo "portable à maj à ". $tabPost[$cpt_utilisateur."_portable_maj_ok"]."<br>";
                $data = array (
                    ':tel_portable' => "0".$tabPost[$cpt_utilisateur."_portable_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateTelPortable($o_conn, $data);
            }
                
            if (isset($tabPost[$cpt_utilisateur."_domicile_maj_ok"])){
                echo "domicile à maj à ". $tabPost[$cpt_utilisateur."_domicile_maj_ok"]."<br>";
                $data = array (
                    ':tel_domicile' => "0".$tabPost[$cpt_utilisateur."_domicile_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateTelDomicile($o_conn, $data);
            }
                

            if (isset($tabPost[$cpt_utilisateur."_bureau_maj_ok"])){
                echo "domicile à maj à ". $tabPost[$cpt_utilisateur."_bureau_maj_ok"]."<br>";
                $data = array (
                    ':tel_bureau' => "0".$tabPost[$cpt_utilisateur."_bureau_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateTelBureau($o_conn, $data);
            }
                

            if (isset($tabPost[$cpt_utilisateur."_rl1_maj_ok"])){
                echo "responsable legal 1 à maj à ". $tabPost[$cpt_utilisateur."_rl1_maj_ok"]."<br>";
                $data = array (
                    ':tel_resp_legal_1' => "0".$tabPost[$cpt_utilisateur."_rl1_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateTelRespLegal1($o_conn, $data);
            }
                
            
            if (isset($tabPost[$cpt_utilisateur."_rl2_maj_ok"])){
                echo "responsable legal 2 à maj à ". $tabPost[$cpt_utilisateur."_rl2_maj_ok"]."<br>";
                $data = array (
                    ':tel_resp_legal_2' => "0".$tabPost[$cpt_utilisateur."_rl2_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateTelRespLegal2($o_conn, $data);
            }
                

            if (isset($tabPost[$cpt_utilisateur."_num_appt_maj_ok"])){
                echo "num appt à maj à ". $tabPost[$cpt_utilisateur."_num_appt_maj_ok"]."<br>";
                $data = array (
                    ':num_appt' => $tabPost[$cpt_utilisateur."_num_appt_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateNumAppt($o_conn, $data);
            }
                

            if (isset($tabPost[$cpt_utilisateur."_residence_maj_ok"])){
                echo "résidence à maj à ". $tabPost[$cpt_utilisateur."_residence_maj_ok"]."<br>";
                $data = array (
                    ':residence' => $tabPost[$cpt_utilisateur."_residence_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateResidence($o_conn, $data);
            }
                
        
            if (isset($tabPost[$cpt_utilisateur."_lieu_maj_ok"])){
                echo "lieu à maj à ". $tabPost[$cpt_utilisateur."_lieu_maj_ok"]."<br>";
                $data = array (
                    ':lieu_dit' => $tabPost[$cpt_utilisateur."_lieu_maj_ok"],
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateLieuDit($o_conn, $data);
            }
               

            if (isset($tabPost[$cpt_utilisateur."_offrecom_maj_ok"])){
                echo "offrecom à maj à ". $tabPost[$cpt_utilisateur."_offrecom_maj_ok"]."<br>";
                $offre_com = 0;
                if ($tabPost[$cpt_utilisateur."_offrecom_maj_ok"] == "OUI"){
                    $offre_com = 1;
                }
                $data = array (
                    ':offre_com' => $offre_com,
                    ':id' => $cpt_utilisateur
                );
                LicencieBase::updateOffreCom($o_conn, $data);
            }
            
            echo "<br>";

           
        }

    }
}