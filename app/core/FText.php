<?php
namespace App\Core;

class FText{
	static function Fnl2br($text){
		return nl2br($text);
	}
	static function Fbr2nl($text){
		$text = preg_replace('/<br\\\\s*?\\/??>/i', "\\n", $text);
	    return str_replace("<br />","\n",$text);
	}
	static function cleanAccents($str, $encoding='utf-8'){
		$str = htmlentities($str, ENT_NOQUOTES, $encoding);
		// remplacer les entités HTML pour avoir juste le premier caractères non accentués
		// Exemple : "&ecute;" => "e", "&Ecute;" => "E", "Ã " => "a" ...
		$str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);
		// Remplacer les ligatures tel que : Œ, Æ ...
		$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
		// Supprimer tout le reste
		$str = preg_replace('#&[^;]+;#', '', $str);
		return $str;
	}
	static function generateString($longueur){
		$caract = array();
		$temp = '';
		for ($i=ord("a");$i<ord("z");$i++){
			array_push($caract,chr($i));
		}
		for ($i=0;$i<10;$i++){
			array_push($caract,$i);
		}
		for ($i=0;$i<$longueur;$i++){
			$temp .= $caract[rand(0,count($caract)-1)];
		}
		return $temp;
	}
	static function cutTexte($texte, $length = 50){
		if (strlen($texte)>=$length){
			$texte = substr($texte,0,$length);
			$espace = strrpos($texte, ' ');
			if ($espace)
				$texte = substr($texte,0,$espace);
			$texte .= '...';
		}
		return $texte;
	}
	static function rewrite($chaine){  
        /* Expression régulière permettant le changement des caractères accentués en 
        * caractères non accentués. 
        */  
        $search = array ('/[éèêëÊË]/u','/[àâäÂÄ]/u','/[îïÎÏ]/u','/[ûùüÛÜ]/u','/[ôöÔÖ]/u','/[ç]/u','/[ ]/u','/[^a-zA-Z0-9_]/u');
		$replace = array ('e','a','i','u','o','c','','');
        $chaine =  preg_replace($search, $replace, $chaine);  
        $chaine = strtolower($chaine);   
        $chaine = str_replace(" ",'',$chaine);   
        $chaine = preg_replace('#\-+#','',$chaine);   
        $chaine = preg_replace('#([-]+)#','',$chaine);  
        trim($chaine);  
        return $chaine; 
    }

    // Virement à communication structurée BE

	static function getVCSBE($string = null){
        if (is_null($string))
            return false;
        $temp = (floor($string / 97)) * 97;
        $modulo = $string - $temp;
        $d = sprintf("%010s",$string);
        $modulo = $modulo == 0 ? 97 : $modulo;
        return sprintf("%s/%s/%s%02d",substr($d,0,3),substr($d,3,4),substr($d,7,3),$modulo);
    }

    /**
     * Crée une communication structurée format EU - ISO 11649
     * Test et documentation http://www.jknc.eu/
     * @param null $string
     * @return string
     */
    static function getVCSEU($string = null){
        if (is_null($string))
            return false;

        $search     = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        $replace    = array('10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35');
        $temp = str_replace($search, $replace, strtolower($string.'RF00'));

        $modulo = bcmod($temp, 97);
        $modulo = sprintf("%02s", 98 - $modulo);
        $modulo = $modulo == 0 ? 97 : $modulo;

        return 'RF'.$modulo.' '.$string;
    }
}
?>