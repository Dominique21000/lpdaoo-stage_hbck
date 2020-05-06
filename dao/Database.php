<?php

include_once 'config/mydb.php';





// class_database.php
class Database
{
    var $baseName = "";
    var $baseUser = "";
    var $userPassW = "";
    var $hostName = "";
    var $connection = "";


    // paramètres passée : valeur par défaut
    function __construct()
    {
        $maConfig = new MyDB();
        //$this->baseName = "hbck_stage_bd";
        $this->baseName = "dominiqukfds1";
        $this->baseUser = $maConfig->getUser();
        $this->userPassW = $maConfig->getPassword();
        $this->hostName = "127.0.0.1";
        //$this->hostName = "dominiqukfds1.mysql.db";
    }

    // stockage de la connexion
    public function makeConnect()
    {
        $dsn = 'mysql:host=' . $this->hostName .';dbname=' . $this->baseName . ";charset=UTF8";
        try {
            $this->connection = new PDO($dsn, $this->baseUser, $this->userPassW);
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
        return $this->connection;
    }
}
