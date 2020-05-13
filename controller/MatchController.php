<?php

class MatchController{
    /* affichage la page des matchs récents */
    public static function displayRecentMatchs($tabGET){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('matchs-recents.html.twig',[
                                'rub' => $tabGET['rub'],
                                "session" => $_SESSION
                            ]);
    }

    /* affichage la page des matchs à venir */
    public static function displayFutursMatchs($tabGET){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('matchs-a-venir.html.twig',
                        ['rub' => $tabGET['rub'],
                        "session" => $_SESSION]
                            );
    }

    /* affichage la page des matchs récents et à venir */
    public static function displayMatchs($tabGET){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        echo $twig->render('matchs.html.twig',
                                ['rub' => $tabGET['rub'],
                                "session" => $_SESSION]
                        );
    }
}