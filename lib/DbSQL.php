<?php
namespace lib;
use PDO;
use PDOException;
class DbSQL{
	private $pdo;
	
	public function __construct($type, $host, $db, $login, $pass, $charset = 'utf8', $debug = false){
		try{
			$options = null;
			if ($debug){
				$options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			}
			$this->pdo = new PDO("{$type}:host={$host};dbname={$db}", $login, $pass, $options);
			
			if($type == 'mysql'){
				$this->pdo->exec("set names {$charset};");
			} 
		} catch (PDOException $e) {			
			die ("Ошибка соединения с БД:<br><pre>{$e->getMessage()}</pre>");

		}
	}
	
	public function request($query){		
		try{
			$result = $this->pdo->query($query);
			return $result;
		} catch (PDOException $e) {			
			die ("Ошибка при запросе к БД:<br><pre>{$e->getMessage()}</pre>");
		}
	}
}
?>