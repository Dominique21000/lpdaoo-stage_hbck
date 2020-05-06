<?php
class Outils{
    /** function 
     * @param data_xls : valeur extrait du fichier xls
     * @return date_naissance sous forme de date aaaa-mm-dd
     */
    public static function convertChiffreToDate($date_xls){
        //   -> date_xls
        var_dump($date_xls);
        //$date_array = (($tabNew[$i]['date_naissance'])-25569) * 86400;
        $date_array = ($date_xls - 25569 * 86400);
        $annee= getdate($date_array)['year']; 
        $mois= getdate($date_array)['mon']; 
        $jour = getdate($date_array)['mday']; 
        
        $date_naissance = $annee . "-" . $mois . "-" . $jour;
        return $date_naissance;
    }

    /** envoi de mail */
    public static function sendMail($to="", $prenom, $nom){

        
        $to      = 'dominique21000@gmail.com';
        $subject = 'HBCK - Vérification de votre email';
        $message = 'Bonjour !';
        $headers = 'From: contact@dominique21000.eu' . "\r\n" .
        'Reply-To: dominique21000@gmail.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
        $headers .= "Content-type: text/plain; charset=utf-8\n";
        //$smtp = "smtp.orange.fr";

        return mail($to, $subject, $message, $headers); 
    }      

    function secureRandom($length = 5) {
        $str = bin2hex(openssl_random_pseudo_bytes($length));
        return strtoupper(substr(base_convert($str, 16, 35), 0, $length));
    }
}