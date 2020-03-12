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

        echo $twig->render('index.html.twig');
    }

    /* affichage la page d'accueil du site */
    public static function accueil(){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('accueil.html.twig');
    }

    /* affichage la page d'accueil du site */
    public static function connexion(){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('connexion.html.twig');
    }
}
