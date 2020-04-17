<?php

// les includes
require_once 'model/Database.php';
require_once 'model/UtilisateurBase.php';

class UtilisateurController {
    

    public static function getList(){
        // connexion à la base
        $o_pdo = new Database();
        $o_conn = $o_pdo->makeConnect();

        // on va chercher la liste des personnes
        $o_ub = new UtilisateurBase();
        $utis = $o_ub->getUtilisateurs($o_conn);

        // envoi de la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                        'cache' => false,
                     ]);
        echo $twig->render('admin/user-list.html.twig',
                                ['utilisateurs' => $utis]);  
    }

    public static function addNewForm($tabPost){
        // on envoit la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                        'cache' => false,
                     ]);
        echo $twig->render("admin/user-form.html.twig",
                        ["mode"=> "new"]);

    }

    public static function addNewBdd($tabPost){
        // connexion à la base
        $o_pdo = new Database();
        $o_conn = $o_pdo->makeConnect();

        $prenom = $_POST["prenom"];
        $nom = $_POST["nom"];
        $email = $_POST["email"];
        $login = $_POST["login"];

        $ub_ajout = UtilisateurBase::add($o_conn, $prenom, $nom, $email, $login);

        if ($ub_ajout == true)
            $message = "L'utilisateur a été correctement ajouté.";
        else
            $message = "Il y a eu un problème durant l'ajout.";
            
            
        // envoi de la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                        'cache' => false,
                     ]);
        echo $twig->render('admin/user-add-result.html.twig',
                                ['message' => $message]);  
    }

    public static function updateForm($tabGET){
        $o_pdo = new Database();
        $o_conn = $o_pdo->makeConnect();
        $ub = new UtilisateurBase();

        $utilisateur = $ub->getById($o_conn, $tabGET['id']);
        //var_dump($utilisateur);
        
        // on envoit la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                        'cache' => false,
                     ]);
        echo $twig->render("admin/user-form.html.twig",
                        ["mode"=> "update",
                        "utilisateur" => $utilisateur]);
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
        
        $bd = new Database();
        $o_conn = $bd->makeConnect();

        $ub_upd = UtilisateurBase::update($o_conn, $id, $prenom, $nom, $email, $login, $actif);

        if ($ub_upd == true)
            $message = "L'utilisateur a été correctement mise à jour.";
        else
            $message = "Il y a eu un problème durant la mise à jour.";
            
            
        // envoi de la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                        'cache' => false,
                     ]);
        echo $twig->render('admin/user-add-result.html.twig',
                                ['message' => $message]);  

    }

    /** send the mail of confirmation of the email adress */
    public static function sendMailVerification($tabGET){
        // recup des infos de l'utilisateur
        // connexion
        $db = new Database();
        $o_conn =$db->makeConnect();

        $utilisateur = UtilisateurBase::getById($o_conn, intval($tabGET));
        $to = $utilisateur['uti_email']; 
        $prenom = $utilisateur['uti_prenom'];
        $nom = $utilisateur['uti_nom']; 

        if (Outils::sendMail($to, $prenom, $nom) == 1)
            $message = "Le mail a été correctement envoyé à $prenom $nom ($to)";
        else
            $message = "Il y a eu un problème durant l'envoi du mail";
            
            
        // envoi de la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                        'cache' => false,
                     ]);
        echo $twig->render('admin/user-add-result.html.twig',
                                ['message' => $message]);  


    }

    /** res */
    public static function mailConfirm($tabGET){
        $id =  intval($tabGET['id']);
        
        $db = new Database();
        $o_conn = $db->makeConnect();
        $res = UtilisateurBase::mailConfirm($o_conn, $id);

        if ($res = true)
            $message = "<span class='oui'>Votre adresse mail a bien été confirmé. <br>Nous vous remercions.</span>";
        else
            $message = "<span clas='non'>Il y a eu une erreur durant le processus.</span>";

        // envoi de la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
                        'cache' => false,
                     ]);
        echo $twig->render('admin/display-result.html.twig',[
                                "rubrique" => "Administration des utilisateurs",
                                "fonctionnalites" => "Confirmation de l'email",
                                "res" => $res,
                                "message" => $message
                                ]
                            );  
    }


}