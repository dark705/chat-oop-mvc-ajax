<?php
namespace lib;
class mCookie{
	private $ttl;
	
	public function __construct($ttl = 1){
		$this->ttl= $ttl;
		
	}
	
	public function get($name){
		
		if (!empty($_COOKIE[$name])){
			return $_COOKIE[$name];
		} else {
			return false;
		}
	}
	
	public function set($name, $prop = false){
		setcookie($name, $prop, time() + 60*60*24*$this->ttl);
		$_COOKIE[$name] = $prop;
	}

}


?>