<?php
class IdentificationDAO {
    /** 
     * récupère le nombre de mot de passe
     */
    public static function getCount($db){
        $sql = "SELECT count(*) as nb FROM lps_Identification;";
        $stmt = $db->prepare($sql);
        $tmp = $stmt->execute();
        $res =$stmt->fetchall(); 
        return $res[0]['nb'];
    }

    /** 
     * renvoi l'id max de la table
     */
    public static function getMaxi($db){
        $sql = "SELECT max(ide_id) as maxi FROM lps_Identification;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $res =$stmt->fetchall(PDO::FETCH_NAMED); 
        return $res[0]['maxi'];
    }

    /**
     * ajoute un element à la table
     * @return id
     */
    public static function add($db, $id, $mdp){
        $sql = "INSERT INTO lps_Identification (ide_id, ide_mdp) VALUES (:id, :mdp);";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":mdp", $mdp, PDO::PARAM_STR);
        return $stmt->execute();
    }
}