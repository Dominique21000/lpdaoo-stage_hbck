<?php

//class Adresse
class AdresseMySQL
{
    //selection tool
    public static function selectAll($dbh)
    {
        $queryHead='SELECT * FROM Adresse;';
        $sth = $dbh->prepare($queryHead);
        $sth->execute();
        $result = $sth->$fetchAll();
        return $result;
    }
}