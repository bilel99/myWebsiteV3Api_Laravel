<?php

namespace App\Helpers;

/**
 * Created by PhpStorm.
 * User: bilel
 * Date: 01/05/2016
 * Time: 23:00
 */
class DatesSecondary
{
    var $today;
    var $tableauJours;
    var $tableauMois;
    var $limitMois;

    function dates($today='')
    {
        if($today == '')
        {
            $this->today = date('Y-m-d');
        }
        else
        {
            $this->today = $today;
        }

        $this->tableauJours['fr'] = array('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche');
        $this->tableauMois['fr'] = array('','Janvier','Février','Mars','Avril','Mai','Juin','Juillet','1oût','Septembre','Octobre','Novembre','Décembre');
        $this->limitMois = array(1=>31,3=>31,4=>30,5=>31,6=>30,7=>31,8=>31,9=>30,10=>31,11=>30,12=>31);
    }

    // Renvoi d'une date formaté comme on le souhaite
    function formatDate($date,$format = 'Y-m-d')
    {
        return date($format,strtotime($date));
    }
    //-----------------------------------------------------------------------------------------
    // ACCESSEUR ------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------

    /**
     * des tableaux de jours/mois gérant les langues ...
     * ajout d'un tableau de mois dans une langue specifique
     */
    function addLnMois($ln = 'en' , $mois = array() )
    {
        $this->tableauMois[$ln] = $mois;
    }

    /**
     * des tableaux de jours/mois gérant les langues ...
     * ajout d'un tableau de jours dans une langue specifique
     */
    function addLnJours($ln = 'en' , $jours = array() )
    {
        $this->tableauJours[$ln] = $jours;
    }

    /**
     * des tableaux de jours/mois gérant les langues ...
     * ajout d'un mois spécifique dans une langue specifique
     */
    function addLnMoisNum($ln = 'en' , $num = 1 , $mois = "January" )
    {
        $this->tableauMois[$ln][$num] = $mois;
    }

    /**
     * des tableaux de jours/mois gérant les langues ...
     * ajout d'un jour spécifique dans une langue specifique
     */
    function addLnJourNum($ln = 'en' ,  $num = 0 , $jours = "Monday" )
    {
        $this->tableauJours[$ln][$num] = $jours;
    }

    //-----------------------------------------------------------------------------------------
    // CONVERTISSEUR --------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------

    /**
     * converti un nombre de secondes pour l'affichage
     */
    function formatTimestampToHour($time)
    {
        if ($time>=86400)
            /* 86400 = 3600*24 c'est à dire le nombre de secondes dans un seul jour ! donc là on vérifie si le nombre de secondes donné contient des jours ou pas */
        {
            // Si c'est le cas on commence nos calculs en incluant les jours

            // on divise le nombre de seconde par 86400 (=3600*24)
            // puis on utilise la fonction floor() pour arrondir au plus petit
            $jour = floor($time/86400);
            // On extrait le nombre de jours
            $reste = $time%86400;

            $heure = floor($reste/3600);
            // puis le nombre d'heures
            $reste = $reste%3600;

            $minute = floor($reste/60);
            // puis les minutes

            $seconde = $reste%60;
            // et le reste en secondes

            // on rassemble les résultats en forme de date
            $result = $jour.'j '.$heure.'h '.$minute.'min '.$seconde.'s';
        }
        elseif ($time < 86400 AND $time>=3600)
            // si le nombre de secondes ne contient pas de jours mais contient des heures
        {
            // on refait la même opération sans calculer les jours
            $heure = floor($time/3600);
            $reste = $time%3600;

            $minute = floor($reste/60);

            $seconde = $reste%60;

            $result = $heure.'h '.$minute.'min '.$seconde.' s';
        }
        elseif ($time<3600 AND $time>=60)
        {
            // si le nombre de secondes ne contient pas d'heures mais contient des minutes
            $minute = floor($time/60);
            $seconde = $time%60;
            $result = $minute.'min '.$seconde.'s';
        }
        elseif ($time < 60)
            // si le nombre de secondes ne contient aucune minutes
        {
            $result = $time.'s';
        }

        return $result;
    }

    /*
     * converti une date mysql au format fr
     */
    function formatDateMysqltoFr($date)
    {
        $d = explode('-',$date);
        return $d[2].'/'.$d[1].'/'.$d[0];
    }


    function formatDateMysqltoShortFR($date)
    {
        $d = explode(' ',$date);
        $d = explode('-',$d[0]);

        $date_fr = $d[2].'/'.$d[1].'/'.$d[0];
        return $date_fr;
    }

    /*
     * converti une date mysql au format fr avec le mois au format alphabetique
     */
    function formatDateMysqltoFrTxtMonth($date, $ln="fr")
    {
        $d = explode(' ',$date);
        $d = explode('-',$d[0]);
        $m = (int)($d[1]);
        $d = (int)($d[2]).' '.$this->tableauMois[$ln][$m].' '.$d[0];
        return $d;
    }

    /**
     * converti une date mysql au format fr en supprimant l'heure
     **/
    function formatDateMysqltoFr_HourOut($date)
    {
        $d = explode(' ',$date);
        $d = explode('-',$d[0]);
        return $d[2].'/'.$d[1].'/'.$d[0];
    }

    /**
     * converti une date mysql au format fr en integrant l'heure
    /*/
    function formatDateMysqltoFr_HourIn($date)
    {
        $h = explode(' ',$date);
        $d = explode('-',$h[0]);

        $date_fr = $d[2].'/'.$d[1].'/'.$d[0].' '.$h[1];
        return $date_fr;
    }

    /*
     * extrait l'heur d'une date mysql
     */
    function formatDateMysqltoHeure($date)
    {
        $d = explode(' ',$date);
        $d = explode(':',$d[1]);
        return $d[0].'h'.$d[1];
    }

    /**
     * converti une date mysql au format fr avec le mois et le jour de la semaine correspondant au format alphabetique
     **/
    function formatDateComplete($date, $ln="fr")
    {
        $d = explode('-',$date);
        $d1 = explode(' ',$d[2]);
        $m = (int)($d[1]);
        $day=(int)($d1[0]);
        $j = date("w",mktime(0,0,0,$m,$day,$d[0]));
        $ladate =  $this->tableauJours[$ln][$j].' '.$day.' '.$this->tableauMois[$ln][$m].' '.$d[0];
        return $ladate;
    }

    /**
     * converti une date mysql en timestamp
     **/
    function formatDateMySqlToTimeStamp($datetime)
    {

        list($date, $time) = explode(' ', $datetime);
        list($year, $month, $day) = explode('-', $date);
        list($hour, $minute, $second) = explode(':', $time);

        $timestamp = mktime($hour, $minute, $second, $month, $day, $year);

        return $timestamp;
    }

    /**
     * converti une date fr au format mysql
     **/
    function formatDateFrToMysql($date)
    {
        $d = explode('/',$date);
        return $d[2].'-'.$d[1].'-'.$d[0];
    }



    /**
     * retourne le nombre d'année entre aujourdhui et la date donnée
     * @param $date au format mysql
     */
    function age($date)
    {
        $d = explode('-',$date);
        $y = date("Y");

        return $y - $d[0];
    }
    /**
     * retournne si $date1 est inferieur à $date2
     * @param $date1
     * @param $date2
     */
    function compareDatetime($date1,$date2)
    {
        return ($this->formatMyToTimeStamp($date1) < $this->formatMyToTimeStamp($date2));
    }
    /**
     * retourne le dernier jour du mois donée dans une année donné
     */
    function nb_jour_dans_mois($mois, $annee)
    {
        switch($mois)
        {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                return 31;
            case 4:
            case 6:
            case 9:
            case 11:
                return 30;
            case 2:
                if(($annee % 4) == 0) return 29;
                else return 28;
        }
        return 31;
    }

    function intervalDates($date1,$date2)
    {
        $dh1 = explode(' ',$date1);
        $d1 = explode('-',$dh1[0]);
        $h1 = explode(':',$dh1[1]);

        $date1 = mktime($h1[0],$h1[1],$h1[2],$d1[1],$d1[2],$d1[0]);

        $dh2 = explode(' ',$date2);
        $d2 = explode('-',$dh2[0]);
        $h2 = explode(':',$dh2[1]);

        $date2 = mktime($h2[0],$h2[1],$h2[2],$d2[1],$d2[2],$d2[0]);

        $diff_date = $date2 - $date1;
        $diff = array();
        $diff['secondes'] = (int)($diff_date);
        $diff['minutes'] = (int)($diff_date/(60));
        $diff['heures'] = (int)($diff_date/(60*60));
        $diff['jours'] = (int)($diff_date/(60*60*24));
        $diff['mois'] = (int)($diff_date/(60*60*24*30));

        return $diff;
    }

    /**
     * retourne le nbre de jour entre deux dates données
     * @param $deb_jour
     * @param $deb_mois
     * @param $deb_annee
     * @param $fin_jour
     * @param $fin_mois
     * @param $fin_annee
     * @return $nb_jours
     */
    function intervalleJours($deb_jour, $deb_mois, $deb_annee, $fin_jour, $fin_mois, $fin_annee)
    {
        $nb_jours = 0;
        for($annee = $deb_annee; $annee <= $fin_annee; $annee++)
        {
            if($annee == $deb_annee) $from_mois = $deb_mois;
            else $from_mois = 1;

            if($annee == $fin_annee) $to_mois = $fin_mois;
            else $to_mois = 12;

            for($mois = $from_mois; $mois <= $to_mois; $mois++)
            {
                if(($mois == $deb_mois) && ($annee == $deb_annee)) $from_jour = $deb_jour;
                else $from_jour = 1;

                if(($mois == $fin_mois) && ($annee == $fin_annee)) $to_jour = $fin_jour;
                else $to_jour = $this->nb_jour_dans_mois($mois, $annee);

                $nb_jours += $to_jour - $from_jour + 1;
            }
        }
        return $nb_jours;
    }
}