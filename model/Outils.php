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
}