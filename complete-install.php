<?php
	include('pbadmin/mysql_connector.php');
	session_cache_limiter('private, must-revalidate');
	$cache_limit = session_cache_limiter();
	session_cache_expire(3);
	$cache_expire = session_cache_expire();
	session_start();
	$mysql = new mysql_class();
	$mysql->filter_var = $_GET['database-host'];
	$db_host = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET['database-name'];
	$db_name = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET['database-username'];
	$db_user = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET['database-password'];
	$db_password = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET['name'];
	$full_name = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET['mail'];
	$mail = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET['password'];
	$password = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET['password-repeat'];
	$password_repeat = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET['photoblog-title'];
	$photoblog_title = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET['photoblog-description'];
	$photoblog_description = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET['photoblog-tags'];
	$photoblog_tags = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET['photoblog-secret'];
	$photoblog_secret = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET['timezone'];
	$timezone = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET['biography'];
	$biography = $mysql->filter_string_xss();
	$mysql->filter_var = $_GET['captcha'];
	$captcha_input = $mysql->filter_string_xss();
	$mysql->filter_var = $_SESSION["text_captcha"];
	$captcha_session = $mysql->filter_string_xss();
	if ($password === $password_repeat) {
		$password_passed = true;
		echo "pass";
			if ($captcha_session === $captcha_input) {
				$captcha_passed = true;
				echo "pass";
				$mysql->user = $db_user;
				$mysql->password = $db_password;
				$mysql->host = $db_host;
				$connection_mysql = $mysql->connection();
				if (!is_object($connection_mysql)) {
					die("error");
				} else if (is_object($connection_mysql)) {
					$mysql->pdo_connection = $connection_mysql;
					$mysql->type = 'othernovar';
					$mysql->query_str = "CREATE DATABASE `$db_name` DEFAULT CHARACTER SET utf8;
USE `$db_name`;
CREATE TABLE IF NOT EXISTS `photoblog_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
	`user_name` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `user_mail` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `user_password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `user_temporal` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `user_timezone` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  `user_transient` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `user_biography` text COLLATE utf8_unicode_ci,
  `user_photoblog_title` text COLLATE utf8_unicode_ci NOT NULL,
  `user_photoblog_description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `user_photoblog_keywords` mediumtext COLLATE utf8_unicode_ci NOT NULL,
	`user_secret_key` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE IF NOT EXISTS `photoblog_photo` (
  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_file` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `photo_name` text COLLATE utf8_unicode_ci NOT NULL,
  `photo_description` longtext COLLATE utf8_unicode_ci,
  `photo_date` date NOT NULL,
  `photo_time` time NOT NULL,
  `photo_tags` text COLLATE utf8_unicode_ci,
  `photo_user` int(11) NOT NULL,
  PRIMARY KEY (`photo_id`),
  KEY `photo_user_fk` (`photo_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE IF NOT EXISTS `photoblog_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `category_description` text COLLATE utf8_unicode_ci,
  `category_user` int(11) NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `category_user_fk` (`category_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE IF NOT EXISTS `photoblog_categories` (
  `categories_id` int(11) NOT NULL AUTO_INCREMENT,
  `categories_photo_id` int(11) NOT NULL,
  `categories_category_id` int(11) NOT NULL,
  `categories_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`categories_id`),
  KEY `categories_user_id` (`categories_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `photoblog_category`
  ADD CONSTRAINT `category_user_fk` FOREIGN KEY (`category_user`) REFERENCES `photoblog_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `photoblog_photo`
  ADD CONSTRAINT `photo_user_fk` FOREIGN KEY (`photo_user`) REFERENCES `photoblog_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `photoblog_categories`
  ADD CONSTRAINT `categories_fk` FOREIGN KEY (`categories_user_id`) REFERENCES `photoblog_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;";
					$mysql->variables = '';
					$mysql->query();
					$hashed_password = hash('whirlpool',$password);
					$hashed_temporal = hash('whirlpool',$photoblog_secret.mt_rand(300000,1000000).$mail);
					$hashed_transient_password = hash('whirlpool',$photoblog_secret.mt_rand(100000,900000).$mail);
					$secret = hash('whirlpool',$photoblog_secret);
					$mysql->type = 'other';
					$mysql->query_str = "INSERT INTO photoblog_user (user_name, user_mail, user_password, user_temporal, user_timezone, user_transient, user_biography, user_photoblog_title, user_photoblog_description, user_photoblog_keywords, user_secret_key) VALUES (:user_name,:user_mail,:user_password,:user_temporal,:user_timezone,:user_transient,:user_biography,:user_phototitle,:user_photodesc,:user_photokeywords,:user_secretkey);";
					$mysql->variables = array(':user_name'=>$full_name,':user_mail'=>$mail,':user_password'=>$hashed_password,':user_temporal'=>$hashed_temporal,':user_timezone'=>$timezone,':user_transient'=>$hashed_transient_password,':user_biography'=>$biography,':user_phototitle'=>$photoblog_title,':user_photodesc'=>$photoblog_description,':user_photokeywords'=>$photoblog_tags,':user_secretkey'=>$secret);
					$mysql->query();
					$connection_mysql = null;
					$config_string = '<?php
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
	$set_database->hostname = "'.$db_host.'";
	$set_database->database = "'.$db_name.'";
	$set_database->username = "'.$db_user.'";
	$set_database->password = "'.$db_password.'";
	$db_connection = $set_database->connect_do();
?>';
					$config_path = getcwd()."/pbadmin/config.php";
					exec("touch $config_path");
					exec("chmod 775 $config_path");
					$config_file = fopen($config_path, "w") or die("Can't create PHP config file!");
					fwrite($config_file, $config_string);
					fclose($config_file);
				}
			} else {
				$captcha_passed = false;
				session_destroy();
				die("error in captcha fields");
			}
	} else {
		$password_passed = false;
		session_destroy();
		die("error in password fields");
	}
	session_destroy();
?>