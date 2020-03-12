<?php
require ('controller/SiteController.php');
require_once 'controller/UtilisateurController.php';

$rub = "";
if (isset($_GET['rub'])) {
    $rub = $_GET['rub'];
};

switch ($rub) {
    case "accueil":
        SiteController::accueil();
        break;

    case "importation":
        UtilisateurController::importation($_POST, $_FILES);
        break;

    case "admin-trt-fichier":
        UtilisateurController::trtFichier($_POST, $_FILES);
        break;

    default:
        SiteController::index();
}

