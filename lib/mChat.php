<?php
namespace lib;
class mChat {
		private $messages;
		public function __construct($messages){
		$this->messages = $messages;
	}
	
	public function show(){
		$htmlMessages = new mTemplate('templates/messages.php', array('messages' => $this->messages->get()));
		$htmlSend = new mTemplate('templates/send.php', array('username' => $this->messages->getUsernameByCookie()));
		$htmlPopup = new mTemplate('templates/popup.php');
		$htmlAll = new mTemplate('templates/main.php', array('templateMessages' => $htmlMessages->get(), 'templateSend' => $htmlSend->get(), 'templatePopup' => $htmlPopup->get()));
		$htmlAll->show();
	}
}
?>