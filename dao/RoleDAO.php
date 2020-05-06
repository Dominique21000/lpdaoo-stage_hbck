<?php
class RoleDAO {
    /** 
     * récupère le nombre de rôle
     */
    public static function getCount($db){
        $sql = "SELECT count(*) as nb FROM lps_Role;";
        $stmt = $db->prepare($sql);
        $tmp = $stmt->execute();
        $res =$stmt->fetchall(); 
        return $res[0]['nb'];
    }

    /** 
     * renvoi l'id max de la table
     */
    public static function getMaxi($db){
        $sql = "SELECT max(lps_ide_id) as maxi FROM lps_Role;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $res =$stmt->fetchall(PDO::FETCH_NAMED); 
        return $res[0]['maxi'];
    }

    /**
     * ajoute un element à la table
     * @return id
     */
    public static function add($db, $id, $libelle){
        $sql = "INSERT INTO lps_Role (rol_id, rol_libelle) VALUES (:id, :libelle);";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":libelle", $mdp, PDO::PARAM_STR);
        return $stmt->execute();
    }

    /** @return la liste des rôles */
    public static function getList($db){
        $sql = "SELECT * FROM lps_Role;";
        $stmt = $db->query($sql);
        return $stmt;
    }

    /** retourne le nombre d'admin */
    public static function getNbAdmin($db){
        $sql = "SELECT count(*) as nb_admin from lps_Disposer ";
        $sql .= " INNER JOIN lps_Role ON lps_Disposer.dis_rol_id = lps_Role.rol_id ";
        $sql .= " where lps_Role.rol_libelle = 'Administrateur';";
        $stmt = $db->query($sql);
        $res = $stmt->fetchall();
        return $res['0']['nb_admin'];
    }
}