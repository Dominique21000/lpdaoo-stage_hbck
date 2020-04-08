<?php
class UtilisateurBase{
    /** recupere les données de l'utilisateur dont le mail est passé en paramètre */
    public static function getUtilisateur($db, $data){
        $sql = "SELECT * ";
        $sql .= " FROM Utilisateur ";
        $sql .= " WHERE uti_email = :email";
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        return $res =$stmt->fetchall(); 
    }

    /** recupere les données de l'utilisateur dont le mail est passé en paramètre */
    public static function getUtilisateurs($db){
        $sql = "SELECT DISTINCT * ";
        $sql .= " FROM Utilisateur ";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $res =$stmt->fetchall(PDO::FETCH_NAMED); 
        return $res;
    }

    /** recupere le nombre max des utilisateurs  */
    public static function getMaxiUtilisateur($db){
        $sql = "SELECT MAX(uti_id) as maxi";
        $sql .= " FROM Utilisateur";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $res =$stmt->fetchall(PDO::FETCH_NAMED); 
        return $res[0]['maxi'];
    }

    public static function addUtilisateur($db, $data){
        $sql = "INSERT INTO Utilisateur (";
        $sql .= "uti_id, ";
        $sql .= "uti_tu_id, ";
        $sql .= "uti_du_id, " ;
        $sql .= "uti_num_structure, ";
        $sql .= "uti_nom, ";
        $sql .= "uti_prenom, ";
        $sql .= "uti_sexe, ";
        $sql .= "uti_numero_licence, ";
        $sql .= "uti_mention, ";
        $sql .= "uti_date_naissance, ";
        $sql .= "uti_email, ";
        $sql .= "uti_mdp, ";
        $sql .= "uti_adresse, ";
        $sql .= "uti_cp, ";
        $sql .= "uti_ville, ";
        $sql .= "uti_lieu_dit, ";
        $sql .= "uti_tel_portable, ";
        $sql .= "uti_tel_bureau, ";
        $sql .= "uti_tel_resp_legal_1, ";
        $sql .= "uti_tel_resp_legal_2, ";
        $sql .= "uti_num_appt, ";
        $sql .= "uti_residence, ";
        $sql .= "uti_offrecom ) ";
        $sql .= " VALUES ( ";
        $sql .= "NULL, ";
        $sql .= "1,";
        $sql .= "1,";
        $sql .= ":num_structure, ";
        $sql .= ":nom, ";
        $sql .= ":prenom, ";
        $sql .= ":sexe, ";
        $sql .= ":numero_licence, ";
        $sql .= ":mention, ";
        $sql .= ":date_naissance, ";
        $sql .= ":email, ";
        $sql .= "'a-fixer',";
        $sql .= ":adresse, ";
        $sql .= ":cp, ";
        $sql .= ":ville, ";
        $sql .= ":lieu_dit, ";
        $sql .= ":tel_portable, ";
        $sql .= ":tel_bureau, ";
        $sql .= ":tel_resp_legal_1, ";
        $sql .= ":tel_resp_legal_2, ";
        $sql .= ":num_appt, ";
        $sql .= ":residence, ";
        $sql .= ":offrecom);";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }
}