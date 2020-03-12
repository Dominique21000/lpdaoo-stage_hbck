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
        SiteController::accueil();
        break;

    case "connexion":
        SiteController::connexion();
        break;

    case "matchs-recents":
        MatchController::displayRecentMatchs();
        break;

    case "matchs-a-venir":
        MatchController::displayFutursMatchs();
        break;

    case "importation":
        UtilisateurController::importation($_POST, $_FILES);
        break;

    case "admin-trt-fichier":
        UtilisateurController::trtFichier($_POST, $_FILES);
        break;

    default:
        SiteController::index();
        break;
}

