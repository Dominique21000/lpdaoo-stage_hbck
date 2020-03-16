<?php
require ('controller/SiteController.php');
require_once 'controller/UtilisateurController.php';
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

    case "importation":
        UtilisateurController::importation($_GET, $_POST, $_FILES);
        break;

    case "admin-trt-fichier":
        UtilisateurController::trtFichier($_GET,$_POST, $_FILES);
        break;
    
    case "ajout-nveau-bdd":
        UtilisateurController::creationNouveauBase($_POST);
        break;


    default:
        $tabGET = array(
            'rub' => "accueil",
        );
        SiteController::accueil($tabGET);
        break;
}

