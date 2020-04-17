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
        SiteController::accueil($_GET);
        break;

    case "connexion":
        SiteController::connexion($_GET);
        break;

    case "matchs":
        MatchController::displayMatchs($_GET);
        break;

    case "matchs-recents":
        MatchController::displayRecentMatchs($_GET);
        break;

    case "matchs-a-venir":
        MatchController::displayFutursMatchs($_GET);
        break;

    case "aide":
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

    default:
        $tabGET = array(
            'rub' => "accueil",
        );
        SiteController::accueil($tabGET);
        break;
}

