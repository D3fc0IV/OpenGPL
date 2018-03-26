<?php
/**
 * Created by PhpStorm.
 * User: FredericD
 * Date: 25-03-18
 * Time: 13:00
 */

namespace App;


class Agent{
    public $nom;
    public $prenom;
    public $rn;

    public function __construct($nom, $prenom){
        $this->nom = $nom;
        $this->prenom = $prenom;
    }


}