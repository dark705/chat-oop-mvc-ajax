<?php
class mMessages {
	private $post;
		
	public function __construct($post, $my){
		$this->post = $post;
		$this->my = $my;
	}
	
	public function get(){
		$query = "SELECT  * FROM `messages_oop` ORDER BY `date_msg` DESC LIMIT 10";
		$res = $this->my->request($query);
		while ($record = $res->fetch_assoc()){
			$arr[] = $record;
		}
		return array_reverse($arr); //JSON_FORCE_OBJECT
	}
	
	public function showJson(){
		echo json_encode($this->get());
	}
	
	public function set(){
		$mObj = json_decode($this->post['transmit']);
		$t = "INSERT INTO `messages_oop` (`date_msg`, `user`, `message`) VALUES (now(), '%s', '%s')";
		$query = sprintf($t, $mObj->user, $mObj->message);
		if ($this->my->request($query));
			echo 'success';
	}
}
?>