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
        SiteController::importation();
        break;

    default:
        SiteController::index();
}

