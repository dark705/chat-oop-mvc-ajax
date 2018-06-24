<?php
interface iMessages{
	public function __construct($post, $my);
	public function get();
	public function set();
	public function showJson();
}

class mMessages{
	protected $post;
		
	public function __construct($post, $my){
		$this->post = $post;
		$this->my = $my;
	}
	
	public function get(){
		$query = "SELECT  * FROM `messages_oop`  ORDER BY `date_msg` DESC LIMIT 50;";;
		$res = $this->my->request($query);
		while ($record = $res->fetch_assoc()){
			$arr[] = $record;
		}
		return array_reverse($arr);
	}
	
	public function showJson(){
		$mObj = json_decode($this->post['receive']);
		$t = "SELECT  * FROM `messages_oop` WHERE `id_msg` > '%d' ORDER BY `date_msg` DESC LIMIT 50;";
		$query = sprintf($t, $mObj->last);
		$res = $this->my->request($query);
		while ($record = $res->fetch_assoc()){
			$arr[] = $record;
		}
		if (isset($arr)){
			echo json_encode(array_reverse($arr)); //JSON_FORCE_OBJECT
		}
		else
			echo 'no';
	}
	
	public function set(){
		$mObj = json_decode($this->post['transmit']);
		$t = "INSERT INTO `messages_oop` (`date_msg`, `user`, `message`) VALUES (now(), '%s', '%s');";
		$query = sprintf($t, $mObj->user, $mObj->message);
		if ($this->my->request($query));
			echo 'success';
		
	}
}
//Можно было бы использовать прокси, но конкретно здесь в этом нет смысла
class mMessagesCheck extends mMessages{
	protected $errors;
	
	public function set(){
		$mObj = json_decode($this->post['transmit']);
		if(!isset($mObj->user) or $mObj->user == '')
			$this->errors[] = 'Введите имя';
		if(!isset($mObj->message) or $mObj->message == '')
			$this->errors[] = 'Нет сообщения';
		
		//доп проверки на SQL инъекции и т.д.
		
		if(is_array($this->errors)){
			echo json_encode($this->errors);
		} else {
			parent::set();
		}
	}
}
?>