<?php
require_once 'model/Database.php';
require_once 'model/UtilisateurBase.php';

class UtilisateurController{
    /** affichage du formulaire de l'importation des données */
    public static function importation($tabGET,$tabPost, $tabFile){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('admin/importation.html.twig',
                                ['rub' => $tabGET['rub']]
                            );
    }

    /** traitement du fichier */
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
            $destination = $_SERVER['DOCUMENT_ROOT'] . $monAdresse .$date_export ."--data.xlsx";
            //echo "origin : " . $origin ."<br>";
            //echo "destin : " . $destination ."<br>";
            //if (copy( $origin, $destination)){
            if (move_uploaded_file( $_FILES['dataFile']['tmp_name'], $destination))
            {
                //echo "Le fichier a été uploadé avec succès.";
                $fichier_joueurs = FFHB::importation($destination);
                $nb_licencies = count($fichier_joueurs);

                // on va chercher les infos de la Table Utilisateur de la base
                $db = new Database();
                $o_conn = $db->makeConnect();
                $ub = new UtilisateurBase();

                $ub_data = $ub->getUtilisateurs($o_conn);
                //print("ubdata : ");
                                
               
                // envoi du résultat à la vue
                $loader = new \Twig\Loader\FilesystemLoader('view');
                $twig = new \Twig\Environment($loader, [
                    'cache' => false,
                ]);


                // on recherche les élements insérer
                $tabNew = FFHB::getElementsNouveaux($fichier_joueurs, $ub_data);
                echo "-----";
                    
                // on recherches les élements déjà présents
                $tabModif = FFHB::getElementsPresents($fichier_joueurs, $ub_data);
                var_dump($tabModif);

                
                //var_dump($ub_data);
                //echo $ub_data[0]["uti_email"];
                
                /*
                echo $twig->render('admin/resultat-import.html.twig', 
                                            ["traitement" => "importation",
                                            "resultat" => "ok",
                                            "nb_licencies" => $nb_licencies,
                                            "fichier_joueurs"=> $fichier_joueurs,
                                            "ub_data"=>$ub_data,
                                            ]
                                );
                */              
            }

            
            //readfile($_FILES['dataFile']['tmp_name']);
         } else {

            //echo "Attaque possible par téléchargement de fichier : ";
            //echo "Nom du fichier : '". $_FILES['dataFile']['tmp_name'] . "'.";

            $loader = new \Twig\Loader\FilesystemLoader('view');
            $twig = new \Twig\Environment($loader, [
                            'cache' => false,
                         ]);

            echo $twig->render('admin/resultat-import.html.twig',
                    ["traitement"=> "importation",
                    'resultat' => "ko"]
                );
         }
    }
}