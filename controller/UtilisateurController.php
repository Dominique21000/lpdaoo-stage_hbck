<?php
session_start();
// les includes
require_once 'dao/Database.php';
require_once 'dao/UtilisateurDAO.php';
require_once 'dao/IdentificationDAO.php';
require_once 'dao/DisposerDAO.php';
require_once 'dao/RoleDAO.php';

class UtilisateurController {
    public static function getList(){
        // connexion à la base
        $o_pdo = new Database();
        $o_conn = $o_pdo->makeConnect();

        // on va chercher la liste des personnes
        $o_ub = new UtilisateurDAO();
        $utis = $o_ub->getUtilisateurs($o_conn);

        // envoi de la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                        'cache' => false,
                     ]);
        echo $twig->render('admin/user-list.html.twig',
                                ['utilisateurs' => $utis,
                                "session" => $_SESSION]);  
    }

    public static function addNewForm($tabPost){
        $db = new Database();
        $o_conn = $db->makeConnect();
        $roles = RoleDAO::getList($o_conn);
        
        // on envoit la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                        'cache' => false,
                     ]);
        echo $twig->render("admin/user-form.html.twig", [
                                "mode"=> "new",
                                "roles" => $roles,
                                "session" => $_SESSION]);

    }

    public static function addNewBdd($tabPost){
        // connexion à la base
        $o_pdo = new Database();
        $o_conn = $o_pdo->makeConnect();

        $prenom = $_POST["prenom"];
        $nom = $_POST["nom"];
        $email = $_POST["email"];
        $login = $_POST["login"];
        $role = intval($_POST["role"]);

        // ajout de l'enreg dans la table lps_Utilisateur
        // calcul de l'id
        $ub = new UtilisateurDAO();
        $id_utilisateur = 1;
        if (UtilisateurDAO::getCount($o_conn) > 0){
            $id_utilisateur = $ub->getMaxi($o_conn) +1; 
        }
        
        $ub_ajout = UtilisateurDAO::add($o_conn, $id_utilisateur, $prenom, $nom, $email, $login);

        // affectation du role à la table lps_Disposer
        $role_ajout = DisposerDAO::add($o_conn, $id_utilisateur, $role);


        if ($ub_ajout == true && $role_ajout == true){
            $message = "L'utilisateur a été correctement ajouté. ";
            
            $chaine = hash("sha256", "HBCK_67-68_".$id_utilisateur);
            $adresse_web = "http://lpdaoo.dominiquesauvignon.eu/stage_hbck/index.php?rub=confirmation-email&id=$id_utilisateur&chaine=$chaine";
            $msg = "Bonjour $prenom $nom,\n";
            $msg .= "Vous avez (ou une personne l'a fait pour vous) crée sur le site HBCK afin afin de pouvoir vous connecter à ce site.\n";
            $msg .= "Merci de bien vouloir cliquez sur le lien suivant afin de valider votre adresse mail : ";
            $msg .= "$adresse_web\n";
            $msg .= "Le site vous invitera ensuite à crérer votre mot de passe. \nFaites atttentions à bien vous en souvenir ensuite, ";
            $msg .= "car nous serons alors dans l'incapacité de vous le redonner. Le seul moyen d'en avoir un nouveau sera alors de contacter l'administrateur de ce site.\n";
            $msg .= "\n\nSportivement\n";
            $msg .= "Le webmaster du HBCK.";
            $sujet = utf8_encode("HBCK - Création d'un nouvel utilisateur");

            if ($envoiMail = Outils::sendMail($email, $prenom, $nom, $sujet, $msg, $id_utilisateur)==1){
                $message .= "Un mail de confirmation de l'adresse mail lui a été envoyé.";
            }
        }
            
            
        else
            $message = "Il y a eu un problème durant l'ajout.";
            
        // envoi de la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                        'cache' => false,
                     ]);
        echo $twig->render('admin/user-add-result.html.twig',
                                ['message' => $message,
                                "session" => $_SESSION]);  
    }

    public static function updateForm($tabGET){
        $o_pdo = new Database();
        $o_conn = $o_pdo->makeConnect();
        $id = intval($tabGET['id']);
        $utilisateur = UtilisateurDAO::getById($o_conn, $id);
        $roles = RoleDAO::getList($o_conn); 
        
        // on envoit la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                        'cache' => false,
                     ]);
        //var_dump($utilisateur, $roles);
        echo $twig->render("admin/user-form.html.twig",
                        ["mode"=> "update",
                        "utilisateur" => $utilisateur,
                        "roles" => $roles,
                        "session" => $_SESSION
                        ]);
                        
    }

    public static function updateBdd($tabPost){
        $id = $tabPost['id'];
        $prenom = $tabPost['prenom'];
        $nom = $tabPost['nom'];
        $email = $tabPost['email'];
        $login = $tabPost['login'];
        $actif =0;
        if (isset($tabPost['actif'])){
            $actif = 1;
        }
        $role = intval($tabPost['role']);
        $role_avant = intval($tabPost['role_avant']);

        $bd = new Database();
        $o_conn = $bd->makeConnect();
        $ok = true;

        // si c'était un admin et que ça l'est plus
        if ($role_avant == 1 && $role >1){
            // suppresion d'un administrateur
            // on vérifie qu'il y en aura encore un
            if (RoleDAO::getNbAdmin($o_conn) == 1 ){
                // page spécial,  il n'y aura plus d'administrateur
                // envoi de la réponse
                $ok = false;
                $loader = new \Twig\Loader\FilesystemLoader('view');
                $twig = new \Twig\Environment($loader, [
                            'cache' => false,
                            ]);
                echo $twig->render('admin/error/user-update-ko-no-admin.html.twig',
                    ["session" => $_SESSION]
                );  
            }
        }


        if ($ok == true){
             // on fait la modif
            // pour l'utilisateur
            $ub_upd = UtilisateurDAO::update($o_conn, $id, $prenom, $nom, $email, $login, $actif);
            // pour la table des role
            $role_update = DisposerDAO::updateRole($o_conn, $id, $role);

            if ($ub_upd == true && $role_update)
                 $message = "L'utilisateur a été correctement mise à jour.";
            else
                 $message = "Il y a eu un problème durant la mise à jour.";
                 
                 
            // envoi de la réponse
            $loader = new \Twig\Loader\FilesystemLoader('view');
            $twig = new \Twig\Environment($loader, [
                             'cache' => false,
                         ]);
            echo $twig->render('admin/user-add-result.html.twig',
                                     ['message' => $message,
                                     "session" => $_SESSION]);  
 
        }
      
    }

    /** send the mail of confirmation of the email adress */
    public static function sendMailVerification($tabGET){
        // recup des infos de l'utilisateur
        // connexion
        $db = new Database();
        $o_conn =$db->makeConnect();
        
        $id = intval($tabGET['id']);
        $utilisateur = UtilisateurDAO::getById($o_conn, intval($tabGET['id']));
        
        // creation du mail
        $to = $utilisateur['uti_email']; 
        $prenom = $utilisateur['uti_prenom'];
        $nom = $utilisateur['uti_nom']; 

        $chaine = hash("sha256", "HBCK_67-68_".$id);
        $adresse_web = "http://lpdaoo.dominiquesauvignon.eu/stage_hbck/index.php?rub=confirmation-email&id=$id&chaine=$chaine";
        $subject = 'HBCK - Vérification de votre email';
        $msg = "Bonjour $prenom $nom,\n";
        $msg .= "Vous avez (ou une personne l'a fait pour vous) crée sur le site HBCK afin afin de pouvoir vous connecter à ce site.\n";
        $msg .= "Merci de bien vouloir cliquez sur le lien suivant afin de valider votre adresse mail : ";
        $msg .= "$adresse_web\n";
        $msg .= "Le site vous invitera ensuite à crérer votre mot de passe. \nFaites atttentions à bien vous en souvenir ensuite, ";
        $msg .= "car nous serons alors dans l'incapacité de vous le redonner. Le seul moyen d'en avoir un nouveau sera alors de contacter l'administrateur de ce site.\n";
        $msg .= "\n\nSportivement\n";
        $msg .= "Le webmaster du HBCK.";
        
        if (Outils::sendMail($to, $prenom, $nom, $subject, $msg, -1) == 1)
            $message = "Le mail a été correctement envoyé à $prenom $nom ($to)";
        else
            $message = "Il y a eu un problème durant l'envoi du mail";
            
        // envoi de la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                        'cache' => false,
                     ]);
        echo $twig->render('admin/user-add-result.html.twig',
                                ['message' => $message,
                                "session" => $_SESSION]);  
    }

    /** appelé lors du clc sur le mail, pour confirmé celui-ci */
    public static function mailConfirm($tabGET){
        $id =  intval($tabGET['id']);
        // envoi de la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                        'cache' => false,
                     ]);

        $ok_hash = false;

        // si lien ok
        if (isset($tabGET['chaine']))
        {
            // verif du hash
            if ($tabGET['chaine'] == hash("sha256", "HBCK_67-68_".$id))
            {
                $ok_hash = true;
                $db = new Database();
                $o_conn = $db->makeConnect();
                UtilisateurDAO::mailConfirm($o_conn, $id);
                
                echo $twig->render('user-mail-ok.html.twig',[
                    "rubrique" => "Administration des utilisateurs",
                    "id" => $id,
                    "session" => $_SESSION ]
                );  
            }
            else{
                $message = "Lien incorrect";
            }
        }

        if ($ok_hash == false){
            $message = "Le lien que vous avez utilisé n'est pas valide.";
            echo $twig->render('admin/display-result.html.twig',[
                "rubrique" => "Administration des utilisateurs",
                "fonctionnalites" => "Confirmation de l'email",
                "res" => false,
                "message" => $message,
                "session" => $_SESSION]
                );
        }
    }

    public static function saisieMdp($tabGET){
        // recup de l'id
        $id = intval($tabGET['id']);
        // envoi de la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                         'cache' => false,
                      ]);
        echo $twig->render('admin/user-saisie_mdp.html.twig',[
                "rubrique" => "Administration des utilisateurs",
                "fonctionnalites" => "(re)création de votre mot de passe",
                "id" => $id ,
                "session" => $_SESSION]
                    ); 
    }

    public static function generationLienEmail($tabGET){
        if (isset($tabGET['id'])){
            $id = intval($tabGET["id"]);
            $db = new Database();
            $o_conn = $db->makeConnect();
            $utilisateur = UtilisateurDAO::getById($o_conn, $id);
            echo "id : " . $id . "<br>";
            echo "login : " .$utilisateur['uti_login']."<br>";
            $chaine = hash("sha256", "HBCK_67-68_".$id);
            $ip = "127.0.0.1";
            //$virtual_path = "/sites/ovh/www/dominiquesauvignon_eu/www/lpdaoo/stage_hbck/";

            $internal_path = "index.php?rub=confirmation-email&id=".$id."&chaine=".$chaine;
            //$link = "http://" . $ip. $virtual_path.$internal_path;
            echo $internal_path;
        }
        else{
            echo "Génération impossible, pas d'id indiqué.";
        }
    }

    public static function savePassword($tabPost){
        $db = new Database();
        $o_conn = $db->makeConnect();
        // recup des données
        $res_id = false;
        if (isset($tabPost['id'])){
            $res_id = true;
            $id_utilisateur = $tabPost['id'];
        }

        $res_mdp1 = false;
        if (isset($tabPost['mdp1'])){
            $res_mdp1 = true;
            $mdp1 = $tabPost['mdp1'];
        }

        $res_mdp2 = false;
        if (isset($tabPost['mdp2'])){
            $res_mdp2 = true;
            $mdp2 = $tabPost['mdp2'];
        }

        $res_mdp = false;
        if ($mdp1 == $mdp2){
            $res_mdp = true;
        }

        if ($res_id && $res_mdp1 && $res_mdp2 && $res_mdp){
            // chiffrage et enregistrement de l'identification
            $mdp_c = hash("sha256", $mdp1);
            $id_identification = 1;
            if (IdentificationDAO::getCount($o_conn) > 0){
                $id_identification =  IdentificationDAO::getMaxi($o_conn) +1; 
            }
            IdentificationDAO::add($o_conn, $id_identification, $mdp_c );

            // par defaut, on lui donne le role de Membre du club
            //$res_ajt = DisposerDAO::add($o_conn, $id_utilisateur, $id_identification, 2);
            $res_update = DisposerDAO::updateIdentification($o_conn, $id_utilisateur, $id_identification);
            //if ($res_ajt == true ){
            if ($res_update == true ){
                $message = "Votre mot de passe a été correctement enregistré.<br>";
                $message .= "Vous pouvez à présent vous connecter. <a href='index.php?rub=conexion'>ici</a>";
                // envoi de la réponse
                $loader = new \Twig\Loader\FilesystemLoader('view');
                $twig = new \Twig\Environment($loader, [
                         'cache' => false,
                      ]);

                      
                echo $twig->render('admin/mdp-ok.html.twig',[
                    "rubrique" => "Administration des utilisateurs",
                    "fonctionnalites" => "(re)création de votre mot de passe",
                    "message" => $message,
                    "res" => true,
                    "session" => $_SESSION]
                    );  
            }
            else
            {
                // envoi de la réponse
                $message = "Il y a eu un problème durant l'enregistrement de votre mot de passe.";
                $loader = new \Twig\Loader\FilesystemLoader('view');
                $twig = new \Twig\Environment($loader, [
                         'cache' => false,
                      ]);
                echo $twig->render('admin/mdp-ok.html.twig',[
                    "rubrique" => "Administration des utilisateurs",
                    "fonctionnalites" => "(re)création de votre mot de passe",
                    "message" => $message,
                    "res" => true,
                    "session" => $_SESSION]
                    );  
            }
        }
    }

    public static function verifIdentifiants($tabPost){
        $identifiant = $tabPost['identifiant'];
        $mdp = $tabPost['mdp'];
        
        // connexion à la base
        $db = new Database();
        $o_conn = $db->makeConnect();
        $mdp_c = hash("sha256", $mdp );

        $db = new Database();
        $o_conn = $db->makeConnect();
        $utilisateur = UtilisateurDAO::checkUser($o_conn, $identifiant, $mdp_c);
        
        if (count($utilisateur)> 0){

            $_SESSION["admin"] = true;
            $login = $utilisateur[0]['uti_login'];
            $_SESSION['id_visiteur'] = $utilisateur[0]['uti_id'];
            $_SESSION['role'] = $utilisateur[0]['rol_libelle'];
            $_SESSION['prenom'] = $utilisateur[0]['uti_prenom'];
            $_SESSION['nom'] = $utilisateur[0]['uti_nom'];

            //envoi de la réponse
            $loader = new \Twig\Loader\FilesystemLoader('view');
            $twig = new \Twig\Environment($loader, [
                         'cache' => false,
                     ]);
            echo $twig->render('user-connexion-ok.html.twig',[
                   "session" => $_SESSION]
                    );
        }
        else{
            $loader = new \Twig\Loader\FilesystemLoader('view');
                $twig = new \Twig\Environment($loader, [
                         'cache' => false,
                      ]);
                echo $twig->render('error/connexion-ko.html.twig',[
                            ]
                    );
        }
    }

    public static function editMyInfos($tabGET){
        $db = new Database();
        $o_conn = $db->makeConnect();
        $utilisateur = UtilisateurDAO::getById($o_conn, $_SESSION["id_visiteur"]);
        //var_dump($utilisateur);
        $roles = RoleDAO::getList($o_conn);

        //envoi de la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                      'cache' => false,
                  ]);
        echo $twig->render('user-edit-informations.html.twig',[
                                "session" => $_SESSION,
                                "utilisateur" => $utilisateur,
                                "roles" => $roles
                            ]
                        );
    }

    /** function pour mettre à jour le mot de passe */
    public static function updateMdp($tabPost){
        $db = new Database();
        $o_conn = $db->makeConnect();
        
        // recup des données
        $res_id = false;
        if (isset($tabPost['id'])){
            $res_id = true;
            $id_utilisateur = $tabPost['id'];
        }

        $res_mdp1 = false;
        if (isset($tabPost['mdp1'])){
            $res_mdp1 = true;
            $mdp1 = $tabPost['mdp1'];
        }

        $res_mdp2 = false;
        if (isset($tabPost['mdp2'])){
            $res_mdp2 = true;
            $mdp2 = $tabPost['mdp2'];
        }

        $res_mdp = false;
        if ($mdp1 == $mdp2){
            $res_mdp = true;
        }

        if ($res_id && $res_mdp1 && $res_mdp2 && $res_mdp)
        {
            // chiffrage et mise à jour du mot de passe 
            $mdp_c = hash("sha256", $mdp1);
            $res_update = IdentificationDAO::updatePassword($o_conn, $id_utilisateur, $mdp_c);

            if ($res_update == true ){
                $message = "Votre mot de passe a été correctement modifié.";
                
                // envoi de la réponse
                $loader = new \Twig\Loader\FilesystemLoader('view');
                $twig = new \Twig\Environment($loader, [
                         'cache' => false,
                      ]);

                      
                echo $twig->render('admin/display-result.html.twig',[
                    "rubrique" => "Administration des utilisateurs",
                    "fonctionnalites" => "Modification de votre mot de passe",
                    "message" => $message,
                    "res" => true,
                    "session" => $_SESSION]
                    );  
            }
            else
            {
                // envoi de la réponse
                $message = "Il y a eu un problème durant l'enregistrement de votre mot de passe.";
                $loader = new \Twig\Loader\FilesystemLoader('view');
                $twig = new \Twig\Environment($loader, [
                         'cache' => false,
                      ]);
                echo $twig->render('admin/display-result.html.twig',[
                    "rubrique" => "Administration des utilisateurs",
                    "fonctionnalites" => "(re)création de votre mot de passe",
                    "message" => $message,
                    "res" => true,
                    "session" => $_SESSION]
                    );  
            }
        }
    }

    public static function oubliPassword(){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                         'cache' => false,
                      ]);
        echo $twig->render('op_saisie-mail.html.twig',[
                    "session" => $_SESSION]
                    );  
    }

    public static function verifEmailEnvoiLien($tabGET){
        $mail = $tabGET['email'];

        // on vérifie le mail
        $db = new Database();
        $o_conn = $db->makeConnect();
        $data = array(
            ":email" => $mail
        );
        $util = UtilisateurDAO::getUtilisateurFromEmail($o_conn, $data);
        if (count($util) > 0 ){
            // mail ok, on génére le lien
            $destinataire = $util[0]["uti_email"];
            $prenom = $util[0]['uti_prenom'];
            $nom = $util[0]['uti_nom'];
            $id = intval($util[0]['uti_id']);
            $chaine = hash("sha256", "HBCK_67-68_".$id);
            $sujet = utf8_encode("HBCK - Regénération de votre mot de passe.");
            
            $msg = "Bonjour $prenom $nom,\n\n";
            $msg .= "Vous (ou une personne pour vous) avez demandé à re créer votre mot de passe.\n\n";
            $adresse_web = "http://lpdaoo.dominiquesauvignon.eu/stage_hbck/index.php?rub=saisie-mdp&id=$id&chaine=$chaine";
            $msg .= "Voici le lien qui vous permettra de re-créer votre mot de passe : " . $adresse_web;
            
            
            $msg .= "\n\nSportivement\n";
            $msg .= "L'équipe du HBCK";

            if (Outils::sendMail($destinataire, $prenom, $nom, $sujet, $msg, $id))
            {
                // envoi réponse de confirmation
                // envoi de la réponse
                $loader = new \Twig\Loader\FilesystemLoader('view');
                $twig = new \Twig\Environment($loader, [
                         'cache' => false,
                      ]);
                
                    echo $twig->render('op_mdp-mail-ok.html.twig',[
                    "rubrique" => "Administration des utilisateurs",
                    "fonctionnalites" => "(re)création de votre mot de passe",
                    "email" => $destinataire,
                    "session" => $_SESSION]
                    );  
            }
            else{
                // envoi de la réponse si pas ko
                $loader = new \Twig\Loader\FilesystemLoader('view');
                $twig = new \Twig\Environment($loader, [
                         'cache' => false,
                      ]);
                
                    echo $twig->render('admin/display-result.html.twig',[
                    "rubrique" => "Administration des utilisateurs",
                    "fonctionnalites" => "(re)création de votre mot de passe",
                    "email" => $destinataire,
                    "res"=> false,
                    "session" => $_SESSION]
                    );  
            }

        }
        else{
            // envoi de la réponse
            $loader = new \Twig\Loader\FilesystemLoader('view');
            $twig = new \Twig\Environment($loader, [
                     'cache' => false,
                  ]);

                $message = "Désolé, votre email n'a pas été reconnu.";
                  
            echo $twig->render('admin/display-result.html.twig',[
                "rubrique" => "Administration des utilisateurs",
                "fonctionnalites" => "Ré création de votre mot de passe.",
                "message" => $message,
                "res" => false,
                "session" => $_SESSION]
                );  
        }
    }
}