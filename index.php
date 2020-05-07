<?php
require ('controller/SiteController.php');
require_once 'controller/UtilisateurController.php';
require_once 'controller/LicenceController.php';
require_once 'controller/MatchController.php';

$rub = "";
if (isset($_GET['rub'])) {
    $rub = $_GET['rub'];
};

switch ($rub) {
    case "accueil":
        // affichage de la page d'accueil
        SiteController::accueil($_GET);
        break;

    case "connexion":
        // affchage de la page de connexion à la zone d'administration
        SiteController::connexion($_GET);
        break;

    case "deconnexion":
        // pour se deconnecter
        SiteController::deconnexion();
    break;

    case "matchs":
        // affiche la liste des matchs
        MatchController::displayMatchs($_GET);
        break;

    case "matchs-recents":
        // affiche la liste des matchs récents
        MatchController::displayRecentMatchs($_GET);
        break;

    case "matchs-a-venir":
        // affiche la liste des matchs à venir
        MatchController::displayFutursMatchs($_GET);
        break;

    case "aide":
        // affiche des informations sur le site
        SiteController::aide($_GET);
        break;

    // pour l'importation depuis le fichie de la fédé
    case "importation":
        LicenceController::importation($_GET, $_POST, $_FILES);
        break;

    case "admin-trt-fichier":
        LicenceController::trtFichier($_GET,$_POST, $_FILES);
        break;
    
    case "ajout-nveau-bdd":
        LicenceController::creationNouveauBase($_POST);
        break;


    case "mise-a-jour":
        LicenceController::majUtilisateur($_POST);
        break;
    // fin trt par lot    

    case "admin-user-list":
        UtilisateurController::getList();
        break;

    case "admin-user-new-form":
        UtilisateurController::addNewForm($_POST);
        break;

    case "admin-user-new-bdd":
        UtilisateurController::addNewBdd($_POST);
        break;

    case "admin-user-update-form":
        UtilisateurController::updateForm($_GET);
        break;
    
    case "admin-user-update-bdd":
        UtilisateurController::updateBdd($_POST);
        break;

    case "envoi-mail":
        UtilisateurController::sendMailVerification($_GET);
        //Outils::sendMail($_GET);
        break;

    case "confirmation-email":
        UtilisateurController::mailConfirm($_GET);
        break;    

    case "generation-email":
        UtilisateurController::generationLienEmail($_GET);
    break;

    case "saisie-mdp":
        UtilisateurController::saisieMdp($_GET);
    break;

    case "sauve-mdp":
        UtilisateurController::savePassword($_POST);
    break;

    case "verif-identifiants":
        // vers la vericiations des identifiants
        UtilisateurController::verifIdentifiants($_POST);
    break;

    case "mes-infos":
        // affiche la page des informations personnelles
        UtilisateurController::editMyInfos($_GET);
    break;

    case "updateMdp":
        UtilisateurController::updateMdp($_POST);
    break;

    default:
        $tabGET = array(
            'rub' => "accueil",
        );
        SiteController::accueil($tabGET);
        break;
}

