<?php
	class connect_db {
		public $hostname;
		public $database;
		public $username;
		public $password;
		public function connect_do() {
			$dsn = "mysql:host=$this->hostname;dbname=$this->database";
			try {
				return new PDO($dsn,$this->username,$this->password);
			} catch (PDOException $error) {
				exit(print("MySQL error: ".$error->getMessage()));
			}
		}
	}
	function filter_xss($filter) {
		$filter_slashes = stripcslashes($filter);
		$filter_html = htmlentities($filter_slashes);
		$filter_html_chars = htmlspecialchars($filter_html);
		return $filter_html_chars;
	}
	$set_database = new connect_db();
	$set_database->hostname = "127.0.0.1";
	$set_database->database = "photoblog";
	$set_database->username = "root";
	$set_database->password = "eDmk0l7849051...";
	$db_connection = $set_database->connect_do();
?>