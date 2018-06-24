<?php
class cMain{
	public function __construct(){
		
	}
	
	public function request(){		
		if(!$_POST){
			$messages = new mMessagesCheck(null);
			$chat = new mChat($messages);
			$chat->show();
		} 
		else {
			$messages = new mMessagesCheck($_POST);
			if (array_key_exists('transmit', $_POST))
				$messages->add();
			if (array_key_exists('receive', $_POST))
				$messages->showJson();
		}
	}
}

?>