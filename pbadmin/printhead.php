<?php
	$configuration_file = "config.php";
	if (file_exists($configuration_file)) {
		require_once('config.php');
	} else {
		exit();
	}
	$site_settings_query = $db_connection->prepare("SELECT user_photoblog_title, user_photoblog_description, user_photoblog_keywords FROM photoblog_user ORDER BY user_id ASC LIMIT 1;");
	$site_settings_query->execute();
	$site_settings = $site_settings_query->fetch();
	$photoblog_title = $site_settings['user_photoblog_title'];
	$photoblog_description = $site_settings['user_photoblog_description'];
	$photoblog_keywords = $site_settings['user_photoblog_keywords'];
	print '<meta name="description" content="'.$photoblog_description.'" />'."\n";
	print '<meta name="keywords" content="'.$photoblog_keywords.'" />'."\n";
	print "\n<title>$photoblog_title</title>\n";
?>