<?php
class LicenceDAO{

    public static function getNbLicence($db){
        $sql = "SELECT COUNT(*) as cpt";
        $sql .= " FROM Licence;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $res =$stmt->fetchall();
        return $res[0]['cpt'];

    }

    /** recupere les données de l'utilisateur dont le mail est passé en paramètre */
    public static function getLicence($db, $data){
        $sql = "SELECT * ";
        $sql .= " FROM Licence ";
        $sql .= " WHERE lic_email = :email";
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        return $res =$stmt->fetchall(); 
    }

    /** recupere les données des tous les licenciés */
    public static function getLicences($db){
        $sql = "SELECT DISTINCT * ";
        $sql .= " FROM Licence ";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $res =$stmt->fetchall(PDO::FETCH_NAMED); 
        return $res;
    }

    /** recupere le nombre max des utilisateurs  */
    public static function getMaxiLicence($db){
        $sql = "SELECT MAX(lic_id) as maxi";
        $sql .= " FROM Licence";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $res =$stmt->fetchall(PDO::FETCH_NAMED);
        return $res[0]['maxi'];
    }

    public static function addLicence($db, $data){
        $sql = "INSERT INTO Licence (";
        $sql .= "lic_id, ";
        $sql .= "lic_num_structure, ";
        $sql .= "lic_nom, ";
        $sql .= "lic_prenom, ";
        $sql .= "lic_sexe, ";
        $sql .= "lic_numero_licence, ";
        $sql .= "lic_mention, ";
        $sql .= "lic_date_naissance, ";
        $sql .= "lic_email, ";
        $sql .= "lic_rue, ";
        $sql .= "lic_cp, ";
        $sql .= "lic_ville, ";
        $sql .= "lic_lieu_dit, ";
        $sql .= "lic_tel_portable, ";
        $sql .= "lic_tel_domicile, ";
        $sql .= "lic_tel_bureau, ";
        $sql .= "lic_tel_responsable_legal_1, ";
        $sql .= "lic_tel_responsable_legal_2, ";
        $sql .= "lic_num_appt, ";
        $sql .= "lic_residence, ";
        $sql .= "lic_offrecom ) ";
        $sql .= " VALUES ( ";
        $sql .= ":id, ";
        $sql .= ":num_structure, ";
        $sql .= ":nom, ";
        $sql .= ":prenom, ";
        $sql .= ":sexe, ";
        $sql .= ":numero_licence, ";
        $sql .= ":mention, ";
        $sql .= ":date_naissance, ";
        $sql .= ":email, ";
        $sql .= ":rue, ";
        $sql .= ":cp, ";
        $sql .= ":ville, ";
        $sql .= ":lieu_dit, ";
        $sql .= ":tel_portable, ";
        $sql .= ":tel_domicile, ";
        $sql .= ":tel_bureau, ";
        $sql .= ":tel_responsable_legal_1, ";
        $sql .= ":tel_responsable_legal_2, ";
        $sql .= ":num_appt, ";
        $sql .= ":residence, ";
        $sql .= ":offrecom);";
        $stmt = $db->prepare($sql);
        //var_dump($stmt);
        $res = $stmt->execute($data);
        //var_dump($data);
        return $res;
    }

    /** met à jour la structure */ 
    public static function updateStructure($db, $data){
        $sql = "UPDATE Licence SET lic_num_structure = :num_structure WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** met à jour le prenom */ 
    public static function updatePrenom($db, $data){
        $sql = "UPDATE Licence SET lic_prenom = :prenom WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** met à jour le nom */ 
    public static function updateNom($db, $data){
        $sql = "UPDATE Licence SET lic_nom = :nom WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /**  on met à jour le sexe */
    public static function updateSexe($db, $data){
        $sql = "UPDATE Licence SET lic_sexe = :sexe WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le numéro de licence */
    public static function updateNumeroLicence($db, $data){
        $sql = "UPDATE Licence SET lic_numero_licence = :numero_licence WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le numéro de licence */
    public static function updateMention($db, $data){
        $sql = "UPDATE Licence SET lic_mention = :mention WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le numéro de licence */
    public static function updateDateNaissance($db, $data){
        $sql = "UPDATE Licence SET lic_date_naissance = :date_naissance WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le mail  */
    public static function updateEmail($db, $data){
        $sql = "UPDATE Licence SET lic_email = :email WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }
    
    /** on met à jour la rue */
    public static function updateRue($db, $data){
        $sql = "UPDATE Licence SET lic_rue = :rue WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le cp */
    public static function updateCp($db, $data){
        $sql = "UPDATE Licence SET lic_cp = :cp WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour la ville */
    public static function updateVille($db, $data){
        $sql = "UPDATE Licence SET lic_ville = :ville WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le tel portable */
    public static function updateTelPortable($db, $data){
        $sql = "UPDATE Licence SET lic_tel_portable = :tel_portable WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

     /** on met à jour le tel domicile */
     public static function updateTelDomicile($db, $data){
        $sql = "UPDATE Licence SET lic_tel_domicile = :tel_domicile WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le tel bureau */
     public static function updateTelBureau($db, $data){
        $sql = "UPDATE Licence SET lic_tel_bureau = :tel_bureau WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le tel responsable légal 1 */
    public static function updateTelRespLegal1($db, $data){
        $sql = "UPDATE Licence ";
        $sql .= " SET lic_tel_responsable_legal_1 = :tel_resp_legal_1 ";
        $sql .= " WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le tel responsable légal 1 */
    public static function updateTelRespLegal2($db, $data){
        $sql = "UPDATE Licence ";
        $sql .= " SET lic_tel_responsable_legal_2 = :tel_resp_legal_2 ";
        $sql .= " WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour le tel responsable légal 1 */
    public static function updateNumAppt($db, $data){
        $sql = "UPDATE Licence ";
        $sql .= " SET lic_num_appt = :num_appt ";
        $sql .= " WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour la résidence */
    public static function updateResidence($db, $data){
        $sql = "UPDATE Licence ";
        $sql .= " SET lic_residence = :residence ";
        $sql .= " WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met à jour la lieu dit */
    public static function updateLieuDit($db, $data){
        $sql = "UPDATE Licence ";
        $sql .= " SET lic_lieu_dit = :lieu_dit ";
        $sql .= " WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met l'offre com à jour */
    public static function updateOffreCom($db, $data){
        $sql = "UPDATE Licence ";
        $sql .= " SET lic_offrecom = :offre_com ";
        $sql .= " WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }

    /** on met l'équipe principale à jour*/
    public static function updateEquipePrincipale($db, $data){
        $sql = "UPDATE Licence ";
        $sql .= " SET lic_offrecom = :offre_com ";
        $sql .= " WHERE lic_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute($data);
        return $res;
    }
}