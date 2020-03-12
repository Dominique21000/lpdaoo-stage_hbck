<?php
class UtilisateurBase{
    /** recupere les données de l'utilisateur dont le mail est passé en paramètre */
    public function getUtilisateur($db, $data){
        $sql = "SELECT * ";
        $sql .= " FROM Utilisateur ";
        $sql .= " WHERE uti_email = :email";
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        var_dump($stmt);
        return $res =$stmt->fetchall(); 
    }

    /** recupere les données de l'utilisateur dont le mail est passé en paramètre */
    public function getUtilisateurs($db){
        $sql = "SELECT * ";
        $sql .= " FROM Utilisateur ";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        //var_dump($stmt);
        $res =$stmt->fetchall(); 
        var_dump($res);
        return $res;

    }
}