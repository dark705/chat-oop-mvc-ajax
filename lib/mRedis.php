<?php
namespace lib;
class mRedis{
	private $redis;
	
	public function __construct($ip = '127.0.0.1', $port = 6379, $pass = null, $db = 1, $key_, $limit){
		if (class_exists('Redis')){
			try {
				$r = new \Redis();
				if ($r->connect($ip, $port) and $r->auth($pass)){
					$r->select($db);
					$this->redis = $r;
					$this->limit = $limit;
					$this->key_ = $key_;
				} else {
					die ('can\'t connect to redis server');
				}
				
			} catch (\RedisException $e) {
				echo $e->getMessage();
			}	
		} else {
			die ('no redis extension');
		}
	}
	
	public function add($val){
		$num = $this->redis->rPush($this->key_, json_encode($val));
		if ($num > $this->limit){
			$this->redis->lPop($this->key_);
		}
	}
	
	public function get(){
		$records = $this->redis->lRange($this->key_, 0, -1);
		if(!is_array($records)){
			return false;
		}
		foreach($records as $record){
			$arr[] = json_decode($record, true);
		}
		return $arr;
	}
	
	public function check(){
		return $this->redis->exists($this->key_);
	}
}

?>