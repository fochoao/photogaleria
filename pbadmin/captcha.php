<?php
  session_cache_limiter('private, must-revalidate');
  $cache_limit = session_cache_limiter();
  session_cache_expire(8);
  $cache_expire = session_cache_expire();
  session_start();
  $alnum_array = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9','0');
  $font_file = '../css/roboto.ttf';
  $captchapath = "../css/captcha.png";
  $x_rand = 30;
  $session_captcha = "";
  session_cache_limiter('private, must-revalidate');
  header("Content-type: image/png");
  $captchapng = imagecreatefrompng($captchapath);
  imagealphablending($captchapng, true);
  imagesavealpha($captchapng, true);
  $gray = imagecolorallocate($captchapng, 128, 128, 128);
  for ($x = 0; $x <= 3; $x++) {
    $angle_rand = mt_rand(0,90);
    $y_rand = mt_rand(28,45);
    $text_random = $alnum_array[mt_rand(0,35)];
    imagettftext($captchapng, 20, $angle_rand, $x_rand, $y_rand, $gray, $font_file, $text_random);
    $x_rand += 30;
    $session_captcha .= $text_random;
  }
  $_SESSION["text_captcha"] = $session_captcha;
  imagepng($captchapng);
  imagedestroy($captchapng);
?>