<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="author" content="Fernando Ochoa Olivares" /><?php
		require_once('pbadmin/printhead.php');
	?><link href="css/main.css" type="text/css" rel="stylesheet" />
	<script src="js/jquery.js" type="/javascript"></script>
	<script src="js/tooltip.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function (){
			$('a[title],input[title],img[title]').Tooltip();
		});
	</script>
</head>
<body onload="start();">
	<div class="header" id="header-margin">
		<p><a href="index.php"<?php require_once('pbadmin/titleprint.php'); ?></p>
	</div>
	<div id="links">
		<p><a href="index.php" title="Start Page" alt="Start Page">Start Page</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="archive.php" title="Archive of Photos" alt="Archive of Photos">Archive of Photos</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="about.php" title="About" alt="About">About</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="blog/index.php" title="My Blog" alt="My Blog">My Blog</a></p>
	</div>
	<br />
	<div id="about" class="about-margin">
	<div id="biography">
	<p>About</p><br /><?php
		require_once('showbiography.php');
	?><br /></div>
	</div>
	<br />
</body>
</html>
