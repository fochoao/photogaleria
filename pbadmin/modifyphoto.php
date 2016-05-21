<?php
	require_once('login.php');
	require_once('config.php');
	if (!empty($_POST['modify-photo'])) {
		$photo_query = $db_connection->prepare("SELECT photo_id, photo_file, photo_user FROM photoblog_photo;");
		$photo_query->execute();
		$photo_results = $photo_query->fetchAll(PDO::FETCH_ASSOC);
		$category_change = "no";
		foreach ($photo_results as $photo) {
			$photo_id = $photo["photo_id"];
			if (!empty($_POST["title-$photo_id"])) {
				$photo_id_json = $photo["photo_id"];
				$photo_file_name = $photo["photo_file"];
				$user_id = $photo["photo_user"];
				$title_photo = filter_xss($_POST["title-$photo_id"]);
				$description_photo = filter_xss($_POST["description-$photo_id"]);
				$tags_photo = filter_xss($_POST["tags-$photo_id"]);
				$get_timezone = $db_connection->prepare('SELECT user_timezone FROM photoblog_user WHERE user_id = :userid;');
				$get_timezone->execute(array(':userid'=>$user_id));
				$set_timezone = $get_timezone->fetch(PDO::FETCH_ASSOC);
				date_default_timezone_set($set_timezone['user_timezone']);
				$date = getdate();
				$date_photo = $date['year'].'-'.$date['mon'].'-'.$date['mday'];
				$time_photo = $date['hours'].':'.$date['minutes'].':'.$date['seconds'];
				$date_time_insert = $db_connection->prepare("UPDATE photoblog_photo SET photo_date = :photodate, photo_time = :phototime WHERE photo_id = :photoid;");
				$date_time_insert->execute(array(':photodate'=>$date_photo,':phototime'=>$time_photo,':photoid'=>$photo_id));
				if (!empty($title_photo)) {
					$modify_insert = $db_connection->prepare("UPDATE photoblog_photo SET photo_name = :photoname WHERE photo_id = :photoid;");
					$modify_insert->execute(array(':photoname'=>$title_photo,':photoid'=>$photo_id));
				}
				if (!empty($description_photo)) {
					$description_insert = $db_connection->prepare("UPDATE photoblog_photo SET photo_description = :photodescription WHERE photo_id = :photoid;");
					$description_insert->execute(array(':photodescription'=>$description_photo,':photoid'=>$photo_id));
				}
				if (!empty($tags_photo)) {
					$tags_insert = $db_connection->prepare("UPDATE photoblog_photo SET photo_tags = :phototag WHERE photo_id = :photoid;");
					$tags_insert->execute(array(':phototag'=>$tags_photo,':photoid'=>$photo_id));
				}
				if (!empty($_FILES["file-$photo_id"]['tmp_name'])) {
					$file_name = $_FILES["file-$photo_id"]['name'];
					$file_size = $_FILES["file-$photo_id"]['size'];
					$file_tmp = $_FILES["file-$photo_id"]['tmp_name'];
					require_once('thumbnail.php');
					$images_dir = "../images/";
					$thumbnails_dir = "../thumbnails/";
					unlink($images_dir.$photo_file_name);
					unlink($thumbnails_dir.$photo_file_name);
					if (preg_match("/(gif|jpg|jpeg|png)$/",strtolower($file_name))) {
						$date = getdate();
						$photo_file_name = 'photo-'.mt_rand(1000, 3000).$date['mday'].strchr(strtolower($file_name), '.');
						$photo_path = $images_dir.$photo_file_name;
						if ($file_size > 10512100) {
							echo "<p>Image is larger than 10MB</p>\n";
						}
						if (move_uploaded_file($file_tmp, $photo_path)) {
							$permissions = 0755;
							chmod($photo_path,$permissions);
							$query_file = $db_connection->prepare('UPDATE photoblog_photo SET photo_file = :photofile WHERE photo_id = :photoid;');
							$query_file->execute(array(':photofile'=>$photo_file_name,':photoid'=>$photo_id));
							make_thumb($images_dir,$photo_file_name,$thumbnails_dir);	
						}
					}
				}
				$delete_category = $db_connection->prepare('DELETE FROM photoblog_categories WHERE categories_photo_id = :photoid;');
				$delete_category->execute(array(':photoid'=>$photo_id));
				$query_categories = $db_connection->prepare('SELECT category_id, category_name FROM photoblog_category;');
				$query_categories->execute();
				$query_category = $query_categories->fetchAll(PDO::FETCH_ASSOC);
				foreach ($query_category as $category) {
					$category_id = $category["category_id"];
					$category_mixed = 'category-'.$category_id;
					$category_current = $_SESSION['category_current'];
					if (isset($_POST[$category_mixed])) {
						$query_insert_category = $db_connection->prepare('INSERT INTO photoblog_categories (categories_photo_id, categories_category_id) VALUES (:photoid, :categoriesid);');
						$query_insert_category->execute(array(':photoid'=>$photo_id,':categoriesid'=>$category_id));
						if ($category_current == $category_id) {
						    $category_change = "current";
						} else if ($category_current == "none") {
						    $category_change = "deleted";
						}
					} else {
						$query_delete_category = $db_connection->prepare('DELETE FROM photoblog_categories WHERE categories_photo_id = :photoid AND categories_category_id = :categoryid;');
						$query_delete_category->execute(array(':photoid'=>$photo_id,':categoryid'=>$category_id));

						if ($category_current == $category_id) {
						    $category_change = "deleted";
						}
					}
				}
			}
		}
		$json = array("photo"=>array("id"=>$photo_id_json,"file"=>$photo_file_name,"uploadphoto"=>$photo_file_name,"name"=>$title_photo,"category"=>$category_change,"modified"=>"yes"));
		print json_encode($json);
	}
?>