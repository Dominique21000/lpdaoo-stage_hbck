<?php

require_once 'vendor/autoload.php';
require_once "model/FFHBModel.php";

class SiteController
{
    /* affichage le logo du site */
    public static function index(){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('index.html.twig',
            ["session" => $_SESSION]);
    }

    /* affichage la page d'accueil du site */
    public static function accueil($tabGET){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('matchs.html.twig',
                ['rub' => $tabGET['rub'],
                "session" => $_SESSION]);
    }

    /* page de listage des functionnalités du site */
    public static function aide($tabGET){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('a-propos.html.twig', 
                                    ['rub' => $tabGET['rub'],
                                    "session" => $_SESSION]);
    }

    /* affichage la page d'accueil du site */
    public static function connexion($tabGET){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('connexion.html.twig',
                            ['rub' => $tabGET['rub'],
                            "session" => $_SESSION]);
    }

    /** deconnexion : renvoie à la page d'accueil */
    public static function deconnexion(){
        $_SESSION['nom'] = "";
        $_SESSION['prenom'] = "";
        $_SESSION['login'] = "";
        $_SESSION['role'] = "";

        // envoi de la réponse
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('matchs.html.twig',
                            ["session" => $_SESSION]);

    }
}
