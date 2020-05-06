<?php
class DisposerDAO{
    /** 
     * récupère le nombre d'association
     */
    public static function getCount($db){
        $sql = "SELECT count(*) as nb FROM lps_Disposer;";
        $stmt = $db->prepare($sql);
        $tmp = $stmt->execute();
        $res =$stmt->fetchall(); 
        return $res[0]['nb'];
    }

    /** 
     * renvoi l'id max de la table d'association
     */
    public static function getMaxi($db){
        $sql = "SELECT max(dis_id) as maxi FROM lps_Disposer;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $res =$stmt->fetchall(PDO::FETCH_NAMED); 
        return $res[0]['maxi'];
    }

    /**
     * ajoute un element à la table
     * @param $db : la connexion
     * @param uti_id : l'identifiant de l'utilisateur
     * @param rol_id : l'identifiant du role
     * @return boolean
     */
    public static function add($db, $uti_id, $rol_id){
        // calcul de l'identifiant de la table
        $id_disp = 1;
        if (DisposerDAO::getCount($db) > 0){
            $id_disp = DisposerDAO::getMaxi($db) + 1; 
        };
        $sql = "INSERT INTO lps_Disposer (dis_id, dis_uti_id, dis_rol_id) VALUES (:id, :uti, :rol );";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":id", intval($id_disp), PDO::PARAM_INT);
        $stmt->bindParam(":uti", intval($uti_id), PDO::PARAM_INT);
        $stmt->bindParam(":rol", $rol_id, PDO::PARAM_INT);
        var_dump($stmt);
        return $stmt->execute();
    }



    /** met à jour la table avec le role 
     * @param uti_id : id de l'utilisateur
     * @param rol_id : id du role
     * */
    public static function updateRole($db, $uti_id, $rol_id){
        $sql = "UPDATE lps_Disposer set dis_rol_id = :role ";
        $sql .= " WHERE dis_uti_id = :uti";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":role", $rol_id);
        $stmt->bindParam(":uti", $uti_id);
        return $stmt->execute();
    }

    /** met à jour la table avec le mot de passe (identification) 
     * @param uti_id : id de l'utilisateur
     * @param ide_id : id du role
     * */
    public static function updateIdentification($db, $uti_id, $ide_id){
        $uti = intval($uti_id);
        $sql = "UPDATE lps_Disposer SET dis_ide_id = :ide_id ";
        $sql .= " WHERE dis_uti_id = :uti";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":ide_id", $ide_id);
        $stmt->bindParam(":uti", $uti);
        return $stmt->execute();
    }
}