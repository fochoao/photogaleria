<?php
	if (file_exists('config.php')) {
		require_once('config.php');
	} else {
		die();
	}
	session_cache_expire(3600);
	$cache_expire = session_cache_expire();
	session_start();
	$server_self = $_SERVER['PHP_SELF'];
	$self_page = trim(strrchr($server_self, "/"), "/");
	$captcha_session = isset($_SESSION['photoblog_login_captcha']);
	if (!empty($_POST["email"]) || !empty($_POST["password"]) || !empty($_POST["captcha"])) {
		$captcha = filter_xss($_POST['captcha']);
		if (!empty($_SESSION['photoblog_login_captcha']) && $_SESSION['photoblog_login_captcha'] == $captcha) {
			$user_mail = filter_xss($_POST['email']);
			$password = hash('whirlpool', filter_xss($_POST['password']));
			$user_result = $db_connection->prepare('SELECT user_password FROM photoblog_user WHERE user_mail = :usermail;');
			$user_result->execute(array(':usermail'=>$user_mail));
			$password_check = $user_result->fetch(PDO::FETCH_ASSOC);
			if (empty($_SESSION['photoblog_max_tries'])) {
				$_SESSION['photoblog_max_tries'] = 0;
			}
			if ($password === $password_check["user_password"]) {
				$temporal_key = hash('whirlpool', $password.mt_rand(1000,2800).$user_mail);
				$_SESSION["temporal_hash"] = $temporal_key;
				$_SESSION['photoblog_max_tries'] = array();
				$temporal_user_db = $db_connection->prepare('UPDATE photoblog_user SET user_temporal = :keysession, user_transient = :keytransient WHERE user_mail = :usermail;');
				$temporal_user_db->execute(array(':keysession'=>$temporal_key,':keytransient'=>'0',':usermail'=>$user_mail));
				exit(header('Location: administrator.php'));
			} else {
				$_SESSION['photoblog_max_tries'] += 1;
				exit(header('Location: index.php'));
			}
		} else {
			exit(header('Location: index.php'));
		}
	} else if (!empty($_SESSION["temporal_hash"])) {
		$temporal_key_session = filter_xss($_SESSION["temporal_hash"]);
		$temporal_key_query = $db_connection->prepare('SELECT user_id, user_mail, user_temporal FROM photoblog_user WHERE user_temporal = :keysession;');
		$temporal_key_query->execute(array(":keysession"=>$temporal_key_session));
		$temporal_data = $temporal_key_query->fetch(PDO::FETCH_ASSOC);
		$temporal_id = $temporal_data["user_id"];
		$temporal_mail = $temporal_data["user_mail"];
		$temporal_key = $temporal_data["user_temporal"];
		if ($temporal_key_session === $temporal_key) {
			$logged_user_id = $temporal_id;
			$password = $temporal_mail.mt_rand(30000,40000).$temporal_id;
			$temporal_key = hash('whirlpool', $password.mt_rand(1000,2800).$user_mail);
			$_SESSION["temporal_hash"] = $temporal_key;
			if ($self_page == 'index.php') {
				exit(header('Location: administrator.php'));
			}
		} else {
			exit(header('Location: index.php'));
		}
	} else if ($self_page == 'administrator.php' && empty($_SESSION["temporal_hash"])) {
		exit(header('Location: index.php'));
	}
?>