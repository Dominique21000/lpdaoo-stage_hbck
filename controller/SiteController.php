<?php

require_once 'vendor/autoload.php';
require_once "model/FFHBModel.php";

class SiteController
{
        /*
    public static function prenom(){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('index.html.twig', ['name' => 'Fabien']);
    }
    */

    public static function index(){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('index.html.twig');
    }

    public static function accueil(){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('accueil.html.twig');
    }

    public static function importation(){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('admin/importation.html.twig');
    }

    public static function trtFichier($tabPost, $tabFile){
        error_reporting(E_ALL | E_STRICT);
        $monAdresse = "intranet/lp-daoo/stage_hbck/public/docs/";

        echo "dans trt fichier";
        if (is_uploaded_file($_FILES['dataFile']['tmp_name'])) {
            echo "File ". $_FILES['dataFile']['name'] ." téléchargé avec succès.<br>";
            echo "Affichage du contenu<br/>";
            $origin = $_FILES['dataFile']['tmp_name'];
            $destination = $_SERVER['DOCUMENT_ROOT'] . $monAdresse .date("Y-m-d--")."data.xlsx";

            echo "origin : " . $origin ."<br>";
            echo "destin : " . $destination ."<br>";
            //if (copy( $origin, $destination)){
            if (move_uploaded_file( $_FILES['dataFile']['tmp_name'], $destination))
            {
                echo "Le fichier a été uploadé avec succès.";
                FFHB::importation($destination);

                
            }

            
            //readfile($_FILES['dataFile']['tmp_name']);
         } else {
            echo "Attaque possible par téléchargement de fichier : ";
            echo "Nom du fichier : '". $_FILES['dataFile']['tmp_name'] . "'.";
         }
         

    }


}
