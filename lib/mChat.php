<?php
namespace lib;
class mChat {
		private $json;
		public function __construct($json){
		$this->json = $json;
	}
	
	
	public function show(){
		$htmlMessages = new mTemplate('templates/messages.php', array('messages'=> $this->json->get()));
		$htmlSend = new mTemplate('templates/send.php');
		$htmlPopup = new mTemplate('templates/popup.php');
		$htmlAll = new mTemplate('templates/main.php', array('templateMessages' => $htmlMessages->get(), 'templateSend' => $htmlSend->get(), 'templatePopup' => $htmlPopup->get()));
		$htmlAll->show();
	}
}
?>