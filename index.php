<?php
require ('controller/SiteController.php');


$rub = "";
if (isset($_GET['rub'])) {
    $rub = $_GET['rub'];
};

switch ($rub) {
    case "hello":
        SiteController::Hello();
        break;

    case "prenom":
        SiteController::prenom();
        break;
    
    case "accueil":
        SiteController::accueil();
        break;

    default:
        SiteController::index();
}

