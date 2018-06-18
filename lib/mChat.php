<?php
class mChat {
		private $json;
		public function __construct($json){
		$this->json = $json;
	}
	
	
	public function show(){
		$htmlMessages = new mInclude('templates/messages.php', array('messages'=> $this->json->get()));
		$htmlSend = new mInclude('templates/send.php');
		$htmlPopup = new mInclude('templates/popup.php');
		$htmlAll = new mInclude('templates/main.php', array('templateMessages' => $htmlMessages->get(), 'templateSend' => $htmlSend->get(), 'templatePopup' => $htmlPopup->get()));
		$htmlAll->show();
	}
}
?>