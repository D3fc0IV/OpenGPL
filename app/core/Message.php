<?php
namespace App\Core;

class Message{
	private $msg;
	private $type; //error, success, information or warning
	private $class = "";
	private $notice;
	private $icon;
	
	
	function __construct($msg = '' ,$type = 'information'){
		$this->msg = $msg;
		$this->type = $type;
	}
	function showMessage(){
		$this->type = $this->type == 'notice' ? 'success' : $this->type;
		switch ($this->type){
			case 'error':
				$this->class 	= 'alert-danger';
				$this->color 	= 'red';
				$this->notice 	= 'Erreur';
				$this->icon 	= 'exclamation-triangle';
			break;
			case 'success':
				$this->class 	= 'alert-success';
				$this->color 	= 'green';
				$this->notice 	= 'Message';
				$this->icon 	= 'check';
			break;
			case 'information' :
				$this->class 	= 'alert-info';
				$this->color 	= 'blue';
				$this->notice 	= 'Information';
				$this->icon 	= 'exclamation';
			break;
			case 'warning' :
				$this->class 	= 'alert-warning';
				$this->color 	= 'orange';
				$this->notice 	= 'Attention';
				$this->icon 	= 'warning';
			break;
		}
		
		$temp = <<<HTML
						<div class="alert alert-block {$this->class}" id="message">
							<button class="close" data-dismiss="alert" type="button">
								<i class="ace-icon fa fa-times"></i>
							</button>
							<i class="ace-icon fa fa-{$this->icon} {$this->color}"></i> 
							<strong class="{$this->color}">
								{$this->notice} : 
							</strong>
							{$this->msg}
						</div>
HTML;
		if ($this->msg != '')
			return $temp;
	}
	function __set($nom,$valeur){
		$this->$nom = $valeur;
	}
	function __tostring(){
		return 'Class Message';
	}
}
?>