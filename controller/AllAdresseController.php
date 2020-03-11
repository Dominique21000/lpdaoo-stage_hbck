<?php

class AllAdresseController
{
    public static function alladresse(){
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);
        echo $twig->render('alladresse.html.twig');
    }
    
}