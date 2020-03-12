<?php

class MatchController{
    /* affichage la page des matchs récents */
    public static function displayRecentMatchs(){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('matchs-recents.html.twig');
    }

    /* affichage la page des matchs à venir */
    public static function displayFutursMatchs(){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('matchs-a-venir.html.twig');
    }
}