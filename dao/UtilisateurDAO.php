<?php
class UtilisateurDAO{
    /** recupere les données de l'utilisateur dont le mail est passé en paramètre */
    public static function getUtilisateurs($db){
        $sql = "SELECT DISTINCT * ";
        $sql .= " FROM lps_Utilisateur ";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $res =$stmt->fetchall(PDO::FETCH_NAMED); 
        return $res;
    }

    /** recupere les données de l'utilisateur dont le mail est passé en paramètre */
    public static function getUtilisateurFromEmail($db, $data){
        $sql = "SELECT * ";
        $sql .= " FROM lps_Utilisateur ";
        $sql .= " WHERE uti_email = :email";
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        return $res =$stmt->fetchall(); 
    }

    public static function getById($db, $id){
        $sql = "SELECT * ";
        $sql .= " FROM lps_Utilisateur ";
        $sql .= " INNER JOIN lps_Disposer ON lps_Utilisateur.uti_id = lps_Disposer.dis_uti_id ";
        $sql .= " WHERE uti_id = :id ";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $res =$stmt->fetchall(); 
        return $res[0];
    }

    /** recupere le nombre max des utilisateurs  */
    public static function getMaxi($db){
        $sql = "SELECT MAX(uti_id) as maxi";
        $sql .= " FROM lps_Utilisateur";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $res =$stmt->fetchall(PDO::FETCH_NAMED); 
        return $res[0]['maxi'];
    }

    /** récupère le nombre d'utilisateurs */
    public static function getCount($db){
        $sql = "SELECT COUNT(*) as nb";
        $sql .= " FROM lps_Utilisateur;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $res =$stmt->fetchall(PDO::FETCH_NAMED); 
        return $res[0]['nb'];
    }

    public static function add($db, $id, $prenom, $nom, $email, $login){
        $sql = "INSERT INTO lps_Utilisateur ";
        $sql .= " (uti_id, uti_nom, uti_prenom, uti_email, uti_login, uti_actif, uti_created_at) VALUES ";
        $sql .= " (:id, :nom, :prenom, :email, :login, 0, now() );";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":nom", $nom, PDO::PARAM_STR);
        $stmt->bindParam(":prenom", $prenom, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":login", $login, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public static function update($db, $id, $prenom, $nom, $email, $login, $actif){
        $sql = "UPDATE lps_Utilisateur SET ";
        $sql .= " uti_nom = :nom,";
        $sql .= " uti_prenom = :prenom, ";
        $sql .= " uti_email = :email, ";
        $sql .= " uti_login = :login, ";
        $sql .= " uti_actif = :actif ";
        $sql .= " where uti_id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":nom", $nom, PDO::PARAM_STR);
        $stmt->bindParam(":prenom", $prenom, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":login", $login, PDO::PARAM_STR);
        $stmt->bindParam(":actif", $actif, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function disable($db, $id){
        $sql = "UPDATE lps_Utilisateur SET ";
        $sql .= " uti_actif = :0 ";
        $sql .= " where uti_id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    } 

    public static function mailConfirm($db, $id){
        $sql = "UPDATE lps_Utilisateur SET ";
        $sql .= " uti_mailconfirme = 1 ";
        $sql .= " where uti_id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function checkUser($db, $login , $mdp){
        
        $sql = "select lps_Utilisateur.uti_login, lps_Role.rol_libelle, lps_Utilisateur.uti_prenom, lps_Utilisateur.uti_nom ";
        $sql .= " from lps_Utilisateur ";
        $sql .= " inner JOIN lps_Disposer on lps_Utilisateur.uti_id = lps_Disposer.dis_uti_id ";
        $sql .= " inner join lps_Identification on lps_Disposer.dis_ide_id=lps_Identification.ide_id ";
        $sql .= " inner join lps_Role on lps_Disposer.dis_rol_id = lps_Role.rol_id ";
        $sql .= " where lps_Utilisateur.uti_login = :login and lps_Identification.ide_mdp = :mdp ";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':mdp', $mdp);
        $stmt->execute();
        $res = $stmt->fetchall(); 
        return $res;
    }
}