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

    public static function importation($tabPost, $tabFile){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('admin/importation.html.twig');
    }

    public static function trtFichier($tabPost, $tabFile){
        $monAdresse = "intranet/lp-daoo/stage_hbck/public/docs/";

        if (is_uploaded_file($_FILES['dataFile']['tmp_name'])) {
            //echo "Fichier ". $_FILES['dataFile']['name'] ." téléchargé avec succès.<br>";
            
            $origin = $_FILES['dataFile']['tmp_name'];
            //$type_fichier = $_FILES['dataFile']['type'];
            //echo "type : " . $type_fichier ."<br>";
                        
            // on récupère la date dans l'onglet
            $xlsx = new XLSXReader($_FILES['dataFile']['tmp_name']);
            $sheets = $xlsx->getSheetNames();
            $date_export = substr($sheets[1],0,10);
            
            // move du fichier
            $destination = $_SERVER['DOCUMENT_ROOT'] . $monAdresse ."/".$date_export ."--data.xlsx";
            //echo "origin : " . $origin ."<br>";
            //echo "destin : " . $destination ."<br>";
            //if (copy( $origin, $destination)){
            if (move_uploaded_file( $_FILES['dataFile']['tmp_name'], $destination))
            {
                //echo "Le fichier a été uploadé avec succès.";
                $joueurs = FFHB::importation($destination);
                $nb_licencies = count($joueurs);


                // envoi du résultat à la vue
                // envoie de la réponse
                $loader = new \Twig\Loader\FilesystemLoader('view');
                $twig = new \Twig\Environment($loader, [
                    'cache' => false,
                ]);

                
                echo $twig->render('admin/resultat.html.twig', 
                                            ["traitement" => "importation",
                                            "resultat" => "ok",
                                            "nb_licencies" => $nb_licencies,
                                            "joueurs"=> $joueurs,
                                            ]);

                        
                        
                    }

            echo $twig->render('alladresse.html.twig', 
            ['adresses' => $adresses]);
            //readfile($_FILES['dataFile']['tmp_name']);
         } else {

            //echo "Attaque possible par téléchargement de fichier : ";
            //echo "Nom du fichier : '". $_FILES['dataFile']['tmp_name'] . "'.";

            $loader = new \Twig\Loader\FilesystemLoader('view');
            $twig = new \Twig\Environment($loader, [
                            'cache' => false,
                         ]);

            echo $twig->render('admin/resultat.html.twig',
                    ["traitement"=> "importation",
                    'resultat' => "ko"]);
         }
    }
}
