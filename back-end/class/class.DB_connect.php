<?php

include_once $_SERVER["DOCUMENT_ROOT"] ."/back-end/config/db_config.php";

class DB_connect{
	public $db;
	
	public function __construct() {
		try {
			$this->db = new PDO("mysql:host=". DB_HOST .";dbname=". DB_NAME, DB_USER_NAME, DB_USER_PASS);
		} catch(PDOException $e) {
			exit("Помилка підключення ". $e->getMessage());
		}
	}
}

?>