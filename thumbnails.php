<?php
	require_once('pbadmin/config.php');
	$thumbnail_dir = "thumbnails/";
	$photo_dir = "images/";
	$query_thumbs_id = $db_connection->prepare("SELECT photo_id, photo_name, photo_file FROM photoblog_photo WHERE photo_id < ? ORDER BY photo_id DESC LIMIT 8;");
	$query_thumbs_id->execute(array($photo_id+1));
	$result_thumbs = $query_thumbs_id->fetchAll(PDO::FETCH_ASSOC);
	$query_thumb = $db_connection->prepare("SELECT photo_id, photo_name, photo_file FROM photoblog_photo WHERE photo_id = ?;");
	$query_thumb->execute(array($photo_id));
	$result_thumb = $query_thumb->fetch(PDO::FETCH_ASSOC);
	$current_thumbnail_id = $result_thumb['photo_id'];
	$current_thumbnail_name = $result_thumb['photo_name'];
	$current_thumbnail_file = $result_thumb['photo_file'];
	$lengthy = 0;
	$lengthl = 10;
	function print_thumbnail($optional_id_set) {
		global $lengthy;
		global $lengthl;
		global $thumbnail_id;
		global $thumbnail_name;
		global $thumbnail_dir;
		global $thumbnail_file;
		print '<a href="index.php?show_image='.$thumbnail_id.'" title="'.$thumbnail_name.'" alt="'.$thumbnail_name.'" ';
		if ($optional_id_set == 1) {
			print 'id="thumbnail-current" ';
		} else {
			print 'id="thumbnail-hilit" ';
		}
		print 'style="top:'.$lengthy.'px; position: absolute;">';
		print '<img src="'.$thumbnail_dir.$thumbnail_file.'" width="50" height="50" title="'.$thumbnail_name.'" alt="'.$thumbnail_name.'" /></a>'."\n";
	}
	foreach ($result_thumbs as $thumbnail) {
		$thumbnail_file = $thumbnail['photo_file'];
		$thumbnail_name = $thumbnail['photo_name'];
		$thumbnail_id = $thumbnail['photo_id'];
		if (!empty($thumbnail_id)) {
			if ($thumbnail_id == $current_thumbnail_id) {
				$optional_id = 1;
				print_thumbnail($optional_id);
			} else {
				$lengthy += 50;
				$optional_id = 0;
				print_thumbnail($optional_id);
			}
		}
	}
?>