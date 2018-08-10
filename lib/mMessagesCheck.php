<?php
namespace lib;
interface iMessages{
	public function __construct($post);
	public function get();
	public function add();
	public function getUsernameByCookie();
	public function showJson();
}

class mMessages implements iMessages{
	private $post;
	private $sql;
	private $cookie;
	private $dbConfig;
	
	public function __construct($post){
		$this->post = $post;
		$this->dbConfig = $dbConfig = new mConfigIni('config/db.ini');
		$this->sql = new DbSQL($dbConfig->type,$dbConfig->host, $dbConfig->db, $dbConfig->login, $dbConfig->pass, 'utf8', true);
		$this->cookie = new mCookie(10);
	}
	
	public function get(){
		$query = "SELECT  * FROM messages_oop  ORDER BY date_msg DESC LIMIT 50;";
		$res = $this->sql->request($query);
		while ($record = $res->fetch(\PDO::FETCH_ASSOC)){
			$arr[] = $record;
		}
		return array_reverse($arr);
	}
	
	public function getUsernameByCookie(){
		return $this->cookie->get('username');
	}
	
	public function showJson(){
		$mObj = json_decode($this->post['receive']);
		$t = "SELECT  * FROM messages_oop WHERE id_msg > '%d' ORDER BY date_msg DESC LIMIT 50;";
		$query = sprintf($t, $mObj->last);
		$res = $this->sql->request($query);
		while ($record = $res->fetch(\PDO::FETCH_ASSOC)){
			$arr[] = $record;
		}
		if (isset($arr)){
			echo json_encode(array_reverse($arr)); //JSON_FORCE_OBJECT
		}
		else
			echo 'no';
	}
	
	public function add(){
		$mObj = json_decode($this->post['transmit']);
		$this->cookie->set('username', $mObj->user);
		
		switch($this->dbConfig->type){
			case 'pgsql':
				$t = "INSERT INTO messages_oop (\"date_msg\", \"user\", \"message\") VALUES (now()::timestamptz(0), '%s', '%s');";
				break;
			case 'mysql':
				$t = "INSERT INTO messages_oop (`date_msg`, `user`, `message`) VALUES (now(), '%s', '%s');";
				break;
		}
		
		$query = sprintf($t, $mObj->user, $mObj->message);
		if ($this->sql->request($query));
			echo 'success';
		
	}
}
//Прокси Защита
class mMessagesCheck implements iMessages{
	private $post;
	
	public function __construct($post){
		$this->post = $post;
	}
	
	public function add(){
		$correct = true;
		$mObj = json_decode($this->post['transmit']);
		if (!isset($mObj->user) or !isset($mObj->message))
			return;
		
		$mObj->user = trim($mObj->user);
		$mObj->message = trim($mObj->message);
		$this->post['transmit'] = json_encode($mObj); //поскольку модифицировали исходный запрос, изменим его и в _POST
		
		// Пробелы	
		if($mObj->user == '' || $mObj->message == '')
			$correct = false;
		//HTML
		if(preg_match("/[<\/][a-zA-Z]{1,10}[>]+/", $mObj->user) or preg_match("/[<\/][a-zA-Z]{1,10}[>]+/", $mObj->message)) 
			$correct = false;
		//SQL
		if(preg_match("/((\%3D)|(=))[^\n]*((\%27)|(\')|(\-\-)|(\%3B)|(;))/i", $mObj->user) or preg_match("/(\%27)|(\')|(\-\-)|(\%23)|(#)/i", $mObj->message)) 
			$correct = false;
		//И т.д 
		
		
		if($correct){
			$real = new mMessages($this->post);
			$real->add();
		}
	}
	
	public function getUsernameByCookie(){ //куки, тоже могли подделать на стороне клиента, 
		$correct = true;
		$real = new mMessages($this->post);
		$userName = $real->getUsernameByCookie();
		if(preg_match("/[<\/][a-zA-Z]{1,10}[>]+/", $userName)) //по этому проверяем как минимум на возможность внедрения HTML и PHP
			$correct = false;
		if ($correct){
			return $userName;
		}
		return false;
	}
	
	
	public function get(){
		$real = new mMessages($this->post);
		return $real->get();
	}
	
	public function showJson(){
		if ($this->post['receive'] != ''){
			$mObj = json_decode($this->post['receive']);
			if (preg_match("/^[\d\+]+$/", $mObj->last)){
				$real = new mMessages($this->post);
				$real->showJson();
			}
		}
	}
	
}
?>