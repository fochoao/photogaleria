<?php
	$site_settings_query = $db_connection->prepare("SELECT user_photoblog_title FROM photoblog_user ORDER BY user_id ASC LIMIT 1;");
	$site_settings_query->execute();
	$site_settings = $site_settings_query->fetch();
	$photoblog_title = $site_settings['user_photoblog_title'];
	$capitalize_title = ucwords(strtolower($photoblog_title));
	print ' title="'.$capitalize_title.'" alt="'.$capitalize_title.'">'.$photoblog_title."</a>";
?>