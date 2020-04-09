<?php
class LicencieBase{

    /** met à jour la structure */ 
    public static function updateStructure($db, $data){
        $sql = "UPDATE Utilisateur SET uti_num_structure = :num_structure WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** met à jour le prenom */ 
    public static function updatePrenom($db, $data){
        $sql = "UPDATE Utilisateur SET uti_prenom = :prenom WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** met à jour le nom */ 
    public static function updateNom($db, $data){
        $sql = "UPDATE Utilisateur SET uti_nom = :nom WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /**  on met à jour le sexe */
    public static function updateSexe($db, $data){
        $sql = "UPDATE Utilisateur SET uti_sexe = :sexe WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le numéro de licence */
    public static function updateNumeroLicence($db, $data){
        $sql = "UPDATE Utilisateur SET uti_numero_licence = :numero_licence WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le numéro de licence */
    public static function updateMention($db, $data){
        $sql = "UPDATE Utilisateur SET uti_mention = :mention WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le numéro de licence */
    public static function updateDateNaissance($db, $data){
        $sql = "UPDATE Utilisateur SET uti_date_naissance = :date_naissance WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le mail  */
    public static function updateEmail($db, $data){
        $sql = "UPDATE Utilisateur SET uti_email = :email WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }
    
    /** on met à jour la rue */
    public static function updateRue($db, $data){
        $sql = "UPDATE Utilisateur SET uti_adresse = :adresse WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le cp */
    public static function updateCp($db, $data){
        $sql = "UPDATE Utilisateur SET uti_cp = :cp WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour la ville */
    public static function updateVille($db, $data){
        $sql = "UPDATE Utilisateur SET uti_ville = :ville WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le tel portable */
    public static function updateTelPortable($db, $data){
        $sql = "UPDATE Utilisateur SET uti_tel_portable = :tel_portable WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

     /** on met à jour le tel domicile */
     public static function updateTelDomicile($db, $data){
        $sql = "UPDATE Utilisateur SET uti_tel_domicile = :tel_domicile WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le tel bureau */
     public static function updateTelBureau($db, $data){
        $sql = "UPDATE Utilisateur SET uti_tel_bureau = :tel_bureau WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le tel responsable légal 1 */
    public static function updateTelRespLegal1($db, $data){
        $sql = "UPDATE Utilisateur ";
        $sql .= " SET uti_tel_resp_legal_1 = :tel_resp_legal_1 ";
        $sql .= " WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le tel responsable légal 1 */
    public static function updateTelRespLegal2($db, $data){
        $sql = "UPDATE Utilisateur ";
        $sql .= " SET uti_tel_resp_legal_2 = :tel_resp_legal_2 ";
        $sql .= " WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le tel responsable légal 1 */
    public static function updateNumAppt($db, $data){
        $sql = "UPDATE Utilisateur ";
        $sql .= " SET uti_num_appt = :num_appt ";
        $sql .= " WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour la résidence */
    public static function updateResidence($db, $data){
        $sql = "UPDATE Utilisateur ";
        $sql .= " SET uti_residence = :residence ";
        $sql .= " WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour la lieu dit */
    public static function updateLieuDit($db, $data){
        $sql = "UPDATE Utilisateur ";
        $sql .= " SET uti_lieu_dit = :lieu_dit ";
        $sql .= " WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met l'offre com à jour */
    public static function updateOffreCom($db, $data){
        $sql = "UPDATE Utilisateur ";
        $sql .= " SET uti_offrecom = :offre_com ";
        $sql .= " WHERE uti_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }
}