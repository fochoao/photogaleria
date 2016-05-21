<?php
	include('pbadmin/mysql_connector.php');
	$mysql = new mysql_class();
	$mysql->filter_var = $_GET["database_host"];
	$db_host = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET["database_name"];
	$db_name = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET["database_username"];
	$db_user = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET["database_password"];
	$db_password = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET["database_password_repeat"];
	$db_password_repeat = $mysql->filter_string_xss();
	if ($db_password === $db_password_repeat) {
	$mysql->user = $db_user;
	$mysql->password = $db_password;
	$mysql->host = $db_host;
	$mysql_result = $mysql->connection();
		if (!is_object($mysql_result)) {
			$json_data = array("db"=>array("mysql"=>"Connection result: ".$mysql_result,"status"=>"succeed"));
		} else if (is_object($mysql_result)) {
			$json_data = array("db"=>array("mysql"=>"Connection result: success!","status"=>"succeed"));
		}
		printf(json_encode($json_data));
	} else {
		$json_data = array("db"=>array("mysql"=>"Type the same password in both fields.","status"=>"succeed"));
		printf(json_encode($json_data));
	}
?>