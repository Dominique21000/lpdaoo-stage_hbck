<?php
require ('controller/SiteController.php');


$rub = "";
if (isset($_GET['rub'])) {
    $rub = $_GET['rub'];
};

switch ($rub) {
    case "accueil":
        SiteController::accueil();
        break;

    case "importation":
        SiteController::importation($_POST, $_FILES);
        break;

    case "admin-trt-fichier":
        SiteController::trtFichier($_POST, $_FILES);
        break;

    case "alladresse":
        SiteController::alladresse();
        break;

    default:
        SiteController::index();
}

