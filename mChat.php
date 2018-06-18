<?php
class mChat {
		private $json;
		public function __construct($json){
		$this->json = $json;
	}
	
	
	public function show(){
		$htmlMessages = new mInclude('vMessages.php', array('messages'=> $this->json->get()));
		$htmlSend = new mInclude('vSend.php');
		$htmlPopup = new mInclude('vPopup.php');
		$htmlAll = new mInclude('vMain.php', array('templateMessages' => $htmlMessages->get(), 'templateSend' => $htmlSend->get(), 'templatePopup' => $htmlPopup->get()));
		$htmlAll->show();
	}
}
?>