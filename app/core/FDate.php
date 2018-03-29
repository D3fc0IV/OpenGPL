<?php
namespace App\Core;

/**
 * Class FDate
 */
class FDate{
	public static $daysName		= array('Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi');
	public static $daysAbr		= array('Di','Lu','Ma','Me','Je','Ve','Sa');
	public static $monthName	= array('janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre');
	public static $monthAbr 	= array('jan.','fév.','mar.','avr.','mai','juin','juil.','aoû.','sep.','oct.','nov.','déc.');
	public static $format		= 'Y-m-d H:i:s';

	static function convert_Eu_Us($date){
        @list ($jour , $mois , $an) = split("[-./]",$date);
        $date = $an."-".$mois."-".$jour; 
        return $date == "--" ? NULL : $date;
    }
    static function getDay($date){
    	$ts = mktime(0,0,0,substr($date,5,2),substr($date,8,2),substr($date,0,4));
    	return self::$daysName[date('w',$ts)];
    }
    static function dateTimeToStr($dateTime){
    	$temp = self::convert_Eu_Us(substr($dateTime,0,10));
    	$temp .= ' à '.substr($dateTime,11,5);
    	return $temp;
    }
    static function dateToTimeLine($dateTime){
		$date = DateTime::createFromFormat(self::$format, $dateTime);
    	$dd = new DateTime(date('Y-m-d'));
    	$temp = new DateTime(substr($dateTime, 0, 10));
    	$interval = $dd->diff($temp);
    	if ($interval->format('%a')>5){
    		return $date->format('j').' '.self::$monthAbr[$date->format('n')-1].' '.$date->format('y'); 
    	}elseif($interval->format('%a')<1){
    		return 'Ce jour à '.$date->format('H:i');
    	}
		elseif($interval->format('%a')==1){
    		return 'Hier à '.$date->format('H:i');	
    	}else{
    		return self::$daysName[$date->format('N')].' à '.$date->format('H:i');
    	}
    }
    static function dateTimeToTS($string){
    	$timestamp = 0;
    	$regexp = "/^[0-9]{4}-[0-9]{2}-[0-9]{2}/";
		if (preg_match($regexp, $string)){
			$hour = 0;
			$minute = 0;
			$second = 0;
			list($date, $time) = explode(' ', $string);
			list($year, $month, $day) = explode('-', $date);
			if (!empty($time))
				list($hour, $minute, $second) = explode(':', $time);
			$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
		}
		return $timestamp;
	}
	static function nbrJourEntre2Date($date1, $date2){
 		$s = strtotime($date2)-strtotime($date1);
 		$d = intval($s/86400)+1;  
 		return "$d";
	} 
    static function getAge($naissance){
		@list($annee, $mois, $jour) = split('[-./]', $naissance);
		$today['mois'] = date('n');
		$today['jour'] = date('j');
		$today['annee'] = date('Y');
		$annees = $today['annee'] - $annee;
		if ($today['mois'] <= $mois) {
			if ($mois == $today['mois']) {
		    	if ($jour > $today['jour'])
		        	$annees--;
		      	}else
		      		$annees--;
		    }
		return $annees;
	}	
	static function decToMin($dec){	
		$tmp=explode(".",$dec);
		$h=$tmp[0];
		if (count($tmp)==1)
			$min="00";
		else{	
			if (strlen($tmp[1])==2){	
				$min=ceil(($tmp[1]*6)/10);
				$min=sprintf("%02d",$min);
			}elseif (strlen($tmp[1])==1){	
				$min=ceil($tmp[1]*6);
				$min=sprintf("%02d",$min);
			}else{
				$tmpval=substr($tmp[1],0,3);
				$min=ceil(($tmpval*6)/100);	
				$min=sprintf("%02d",$min);
			}
		}
		if ($h == 0)
			$h = "00";
		else if ($h<10 && $h != 1)
			$h = "0".$h;
		$time = $h.":".$min.":00";
		return $time;
	}
	static function timeToDec($time){
		$tmp = explode(":",$time);
		return round(($tmp[0])+($tmp[1]/3*5)/100,2); 
	}
	static function timeToSec($time){
		$sign = '';
		$second = 0;
		$time = explode(':',$time);
		if (strstr($time[0],'-')){
			$sign = '-';
			$time[0] = str_replace('-','',$time[0]);
		}
		$second += ($time[0]*3600);
		$second += ($time[1]*60);
		$second += $time[2];
		return $sign.$second;
	}
	static function secToTime($second){
		$sign = '';
		if ($second < 0){
			$sign = '-';
			$second = str_replace('-','',$second);
		}
		$temp = $second % 3600;
		$h = ( $second - $temp ) / 3600 ;
		$s = $temp % 60 ;
		$m = ( $temp - $s ) / 60;
		$h = strlen($h)< 2 ? sprintf("%02d",$h) : $h;
		$s = sprintf("%02d",$s);
		$m = sprintf("%02d",$m);
		return $sign.$h.':'.$m.':'.$s;
	}
	static function nombreMois($date1,$date2){
		$regexp = "/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/";
		if (preg_match($regexp, $date1) && preg_match($regexp, $date2)){
			
			$Td1 = explode('-',$date1);
			$Td2 = explode('-',$date2);
			
			$date = new DateTime();
			$date->setDate($Td1[2], $Td1[1], $Td1[0]);
			$d1 = $date->format('U');
			$date->setDate($Td2[2], $Td2[1], $Td2[0]);
			$d2 = $date->format('U');
			
			$nb = round(((($d2-$d1)/86400)/30),1);
			if(!is_numeric($nb)) {
	            return false;
	        }
	        /*
	        $sup = round($nb);
	        $inf = floor($nb);
	        $try = (double) $inf . '.5' ;
	        if($nb > $try) {
	            return $sup;
	        }	        
	        return $inf;
			*/
			$nb = round($nb,1);
			$temp = explode('.',$nb);
			if ($temp[1]>=5)
				$nb = $temp[0].'.5';
			else
				$nb = $temp[0];
			return $nb; 	
		}
	}
}
?>