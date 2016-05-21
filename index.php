<?php
	session_cache_limiter('private, must-revalidate');
	$cache_limit = session_cache_limiter();
	session_cache_expire(8);
	$cache_expire = session_cache_expire();
	session_start();
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="PhotoblogX" /><?php
			require_once('pbadmin/printhead.php');
		?><link href="css/main.css" type="text/css" rel="stylesheet" />
	    
		<link rel="alternate" href="feed.php" title="RSS feed" type="application/rss+xml" />
		
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/tooltip.js" type="text/javascript"></script>
		<script src="js/pace.js" type="text/javascript"></script>
		<script type="text/javascript">
			function start() {
				$('#loading').hide();
				$('#photo-hide').fadeIn(1000);
				$('.forward').fadeIn(1000);
				$('.back').fadeIn(1000);
				$('#info-show').fadeIn(1000);
				$('#thumbnail-hide').fadeIn(1000);
				$('#social-hide').fadeIn(1000);
			}
			function loadphoto() {
				$('a[title],input[title],img[title],button[title]').Tooltip();
				$('.back, .forward, input').fadeTo(500, 0.60).css('background-color','#333333')
				$('.back, .forward').hover(
					function () {
						$(this).fadeTo(500, 0.90).css('background-color','#111111');
					},
					function () {
						$(this).fadeTo(500, 0.60).css('background-color','#333333');
				});
				$('#info-show').hover(
					function () {
						$('#info-photo').toggle('hide');
					},
					function () {
						$('#info-photo').toggle('display');
				});
				$("#thumbnail-current img").hover(
					function () {
						$(this).fadeTo(400, 0.55);
					},
					function () {
						$(this).fadeTo(500, 1.0);
				});
				$("#thumbnail-hilit img").hover(
					function () {
						$(this).fadeTo(500, 0.55);
					},
					function () {
						$(this).fadeTo(500, 1.0);
				});
			};
			jQuery(document).ready(function() {
				loadphoto();
			});
		</script>
		
	</head>
	<body onload="start();">
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<div class="header">
				<p><a href="index.php"<?php require_once('pbadmin/titleprint.php'); ?></p>
			</div>
			<div id="links">
				<p><a href="index.php" title="Start Page" alt="Start Page">Start Page</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="archive.php" title="Archive of Photos" alt="Archive of Photos">Archive of Photos</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="about.php" title="About" alt="About">About</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="blog/index.php" title="My Blog" alt="My Blog">My Blog</a></p>
			</div>
			<div class="content" id="content-margin">
			<div id="photo-hide" style="display: none;"><?php
				include('photo.php');
			?></div>
			<div class="thumbnail" id="thumbnail-margin">
			<div id="thumbnail-hide" style="display: none;"><?php
				include('thumbnails.php');
			?></div>
			</div>
			<div id="social">
			<div id="social-hide" style="display: none;"><?php
				include('share.php');
			?><script type="text/javascript">
				!function(d,s,id){
					var js,fjs=d.getElementsByTagName(s)[0];
					if(!d.getElementById(id)){js=d.createElement(s);
						js.id=id;
						js.src="//platform.twitter.com/widgets.js";
						fjs.parentNode.insertBefore(js,fjs);}}
				(document,"script","twitter-wjs");
			</script>
			</div>
			</div>
		</div>
	</body>
</html>
