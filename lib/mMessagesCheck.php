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
	private $redis;
	
	public function __construct($post){
		$this->post = $post;
		$this->dbConfig = $dbConfig = new mConfigIni('config/db.ini');
		$this->redisConfig = $redisConfig = new mConfigIni('config/redis.ini');
		$this->sql = new DbSQL($dbConfig->type,$dbConfig->host, $dbConfig->db, $dbConfig->login, $dbConfig->pass, 'utf8', true);
		$this->cookie = new mCookie(10);
		if($redisConfig->status == 'on'){
			$this->redis = new mRedis($redisConfig->ip, $redisConfig->port, $redisConfig->password, $redisConfig->database, $redisConfig->keystorage, 50);
		}
	}
	
	public function get(){
		//если Redis используется, И в нём уже есть сообщения
		if($this->redisConfig->status == 'on' and $this->redis->check()){
			$arr = $this->redis->get();
		} else {
		//если Redis не используется, ИЛИ сообщений в нём ещё нет(холодный старт)
			$query = "SELECT  * FROM messages_oop  ORDER BY date_msg DESC LIMIT 50;";
			$res = $this->sql->request($query);
			while ($record = $res->fetch(\PDO::FETCH_ASSOC)){
				$arr[] = $record;
				if($this->redisConfig->status == 'on'){ //Redis используется, был холодный старт, переносим из SQL БД в Redis
					$this->redis->add($record);
				}
			}
		}
		return array_reverse($arr);
	}
		

	public function showJson(){
		$mObj = json_decode($this->post['receive']);
		$last_id = $mObj->last;
		
		//Если Redis используется и был холодный старт 
		if($this->redisConfig->status == 'on' and !$this->redis->check()){
			$this->get(); //перенесёт сообщения из SQL БД в Redis
		}
		
		//Если Redis используется (на возможность холодного старта, проверили ранее)
		if($this->redisConfig->status == 'on'){
			$red_arr = $this->redis->get();
			foreach($red_arr as $record){
				if ($record['id_msg'] > $last_id){
					$arr[] = $record;
				}
			}
		} else { //Redis не используется
			$t = "SELECT  * FROM messages_oop WHERE id_msg > '%d' ORDER BY date_msg DESC LIMIT 50;";
			$query = sprintf($t, $last_id);
			$res = $this->sql->request($query);
			while ($record = $res->fetch(\PDO::FETCH_ASSOC)){
				$arr[] = $record;
			}
		}
		
		if (isset($arr)){ //если нашли новые(либо в SQL либо Redis),
				echo json_encode(array_reverse($arr)); //выводим
			} else {
				echo 'no'; //если нет, то говорим что нет новых
			}
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
		if ($this->sql->request($query)){
			if($this->redisConfig->status == 'on'){ //Если Redis включён
				//полностью обновляем его из основной бд, иначе id записей в redis и SQL могут разойтись, да и не такие большие накладные расходы
				$query = "SELECT  * FROM messages_oop  ORDER BY date_msg DESC LIMIT 50;";
				$res = $this->sql->request($query);
				while ($record = $res->fetch(\PDO::FETCH_ASSOC)){
					$this->redis->add($record);
				}
			}
			echo 'success';
		}
	}
	
	public function getUsernameByCookie(){
		return $this->cookie->get('username');
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