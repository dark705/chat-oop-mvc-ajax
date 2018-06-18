<?php
class cMain{
	public function __construct(){
		
	}
	
	public function request(){
					$host = 'localhost';
					$db = 'php07';
					$login = 'php07'; 
					$pass = 'php07';
		$mysql = new mMySQL($host, $db, $login, $pass);
		
		if(!$_POST){
			$messages = new mMessages(null, $mysql);
			$chat = new mChat($messages);
			$chat->show();
		} 
		else {
			$messages = new mMessages($_POST, $mysql);
			if (array_key_exists('transmit', $_POST))
				$messages->set();
			if (array_key_exists('receive', $_POST))
				$messages->showJson();
		}
	}
}

?>