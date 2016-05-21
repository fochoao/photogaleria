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
		<meta name="author" content="PhotoblogX" />

		<link href="css/main.css" type="text/css" rel="stylesheet" />
		<link href="js/jquery-ui.css" type="text/css" rel="stylesheet" />
		<style type="text/css">
			input, button, select, option {
				font-size: 14px;
				font-family: android-freefont;
				opacity: .9;
				color: #FFFFFF;
				border: none;
				background-color: #999999;
				-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity = 40)";  
				filter: progid:DXImageTransform.Microsoft.Alpha(Opacity = 40);
			}
			input:hover, button:hover, select:hover, option:hover {
				opacity: .8;
				-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity = 90)";
				filter: Alpha(Opacity = 90);
				color: #FFFFFF;
				background-color: #000000;
			}
 		</style>
		
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/jquery-ui.js" type="text/javascript"></script>
		<script src="js/tooltip.js" type="text/javascript"></script>
		<script src="js/pace.js" type="text/javascript"></script>
		<script type="text/javascript">
			function start() {
				$('#loading').hide();
				$('.header').fadeIn(1000);
				$('.links').fadeIn(1000);
				$('.content').fadeIn(1000);
			}
			function loadphoto() {
				$('a[title],input[title],img[title],button[title],textarea[title],select[title],button[title]').Tooltip();
				$('.header').hide();
				$('.links').hide();
				$('.content').hide();
				$("#captcha").on('click',function(e){
					$(".captcha_img").attr("src", "pbadmin/captcha.php"+'?'+Math.random());
				});
			};
			function custom_alert(textmessage, titlemessage) {
				if (!titlemessage)
					titlemessage = 'Alert';
				if (!textmessage)
					textmessage = 'No Message to Display.';
				
				$("<div></div>").html(textmessage).dialog({
					title: titlemessage,
					resizable: false,
					modal: true,
					buttons: {
						"Close": function() {
							$(this).dialog("close");
						}
					}
				});
			};
			jQuery(document).ready(function() {
				loadphoto();
				$('.mail').keyup(function() {
					var $th = $(this);
					$th.val( $th.val().replace(/[^a-z0-9@._-]/gi, function(str) { custom_alert("Email should only contain the following characters: a to z - _ and @ ", "Set Title"); return ''; } ) );
				});
				$(document).on("click", '#test-connection', function(){
					var db_host = $(".database-host").prop('value');
					var db_name = $(".database-name").prop('value');
					var db_username = $(".database-username").prop('value');
					var db_password = $(".database-password").prop('value');
					var db_password_repeat = $(".database-password-repeat").prop('value');
					$.ajax({
						type: 'GET',
						url: 'install-checkmysql.php',
						data: { database_host : db_host, database_name : db_name, database_username : db_username, database_password : db_password, database_password_repeat : db_password_repeat },
						dataType: 'json',
						cache: false,
						success: function(result_json) {
							if (result_json.db.status == "succeed") {
								var result_mysql = result_json.db.mysql;
								$("#connection-input").html('<p>'+result_mysql+'</p>');
							}
						}
					});
				});
			});
		</script>
		
	</head>
	<body onload="start();">
			<div class="header">
				<p class="install-text"><a href="install.php" title="Install Script - Photogaleria" alt="Install Script - Photogaleria">Install - Photogaleria</a></p>
			</div>
			<div id="links">
				<p class="install-text"><a href="install.php" title="Install Script - Photogaleria" alt="Install Script - Photogaleria">Install - Photogaleria</a></p>
			</div>
			<div class="content" id="content-margin">
				<h1>Prepare to install Photogaleria</h1>
				<br />
				<form action="complete-install.php" method="get">
						<p>Full name <input type="text" alt="Name" title="Name" name="name" class="name" /></p>
						<p>Mail <input type="text" alt="Mail" title="Mail" name="mail" class="mail" /></p>
						<p>Password <input type="password" alt="Password" title="Password" name="password" class="password" /></p>
						<p>Repeat Password <input type="password" alt="Password" title="Password" name="password-repeat" class="password-repeat" /></p>
						<p>Photoblog Title <input type="text" alt="Photoblog Name" title="Photoblog Name" name="photoblog-title" class="photoblog-title" /></p>
						<p>Photoblog Description <input type="text" alt="Photoblog Description" title="Photoblog Description" name="photoblog-description" class="photoblog-description" /></p>
						<p>Photoblog Tags <input type="text" alt="Photoblog Tags" title="Photoblog Tags" name="photoblog-tags" class="photoblog-tags" /></p>
						<p>Photoblog Secret (use a random strong passphrase, not actually needed for logging in) <input type="text" alt="Photoblog Secret" title="Photoblog Secret" name="photoblog-secret" class="photoblog-secret" /></p>
						<p>Timezone <?php include("pbadmin/showtimezone.php"); ?></p>
						<p>Biography (About text, html tags accepted) <br /> <textarea rows="16" cols="70" alt="Biography" title="Biography" name="biography" class="biography"></textarea></p>
						<br /><p>Type the next text (click on image to refresh) </p><p><a href="javascript:void(0);" id="captcha"><img src="pbadmin/captcha.php" class="captcha_img" alt="Captcha Image" title="Captcha" /></a></p>
						<p><input type="text" alt="Captcha" title="Captcha" name="captcha" /></p>
						<br /><p>Database Host (usually just localhost, check your server documentation) <input type="text" alt="Database Host" title="Database Host" name="database-host" class="database-host" /></p>
						<p>Database Name <input type="text" alt="Database Name" title="Database Name" name="database-name" class="database-name" /></p>
						<p>Database Username <input type="text" alt="Database Username" title="Database Username" name="database-username" class="database-username" /></p>
						<p>Database Password <input type="password" alt="Database Password" title="Database Password" name="database-password" class="database-password" /></p>
						<p>Repeat Database Password <input type="password" alt="Repeat Database Password" title="Repeat Database Password" name="database-password-repeat" class="database-password-repeat" /></p>
						<p><button type="button" value="Test Connection" name="test-connection" title="Test Connection" id="test-connection">Test Connection</button></p>
						<div id="connection-input"></div>
						<br /><p><input type="submit" alt="Install" title="Install" name="install" value="Install" id="install-complete" /></p><br />
				</form>
			</div>
		</div>
	</body>
</html>
