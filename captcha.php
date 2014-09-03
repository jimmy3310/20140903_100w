<?php

session_start();
include("/var/www/html/mp/class/simple-php-captcha.php");

$_SESSION['captcha'] = simple_php_captcha(array(
	'min_length' => 5,
	'max_length' => 5,
	'characters' => 'ABCDEFGHJKLMNPRSTUVWXYZ23456789',
	'color' => '#666',
	'angle_min' => 0,
	'angle_max' => 7,
	'shadow' => true,
	'shadow_color' => '#fff',
	'shadow_offset_x' => -1,
	'min_font_size' => 30,
	'max_font_size' => 30,
	'shadow_offset_y' => 1
));





print $_SESSION['captcha']['image_src'];


// $ch = curl_init($url);
// curl_setopt($ch, CURLOPT_HEADER, 0);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
// $image=curl_exec($ch);
// curl_close ($ch);



// $bodyFile = @fopen("/tmp/img.jpg", "w");
// fprintf($bodyFile, "%s", $image);
// fclose($bodyFile);



// header("Content-Type: image/png");
// echo $image;


?>


