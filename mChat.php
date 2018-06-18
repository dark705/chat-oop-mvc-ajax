<?php
class mChat {
		private $json;
		public function __construct($json){
		$this->json = $json;
	}
	
	
	public function show(){
		$htmlMessages = new mInclude('vMessages.php', array('messages'=> $this->json->get()));
		$htmlSend = new mInclude('vSend.php');
		$htmlAll = new mInclude('vMain.php', array('templateMessages' => $htmlMessages->get(), 'templateSend' => $htmlSend->get()));
		$htmlAll->show();
	}
}
?>