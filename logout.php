<?php

session_start();

	header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	

include("/var/www/conf/config.php");


//if(isset($_COOKIE['uid'])) {
						unset($_COOKIE['uid']);
						setcookie('uid', '', time() - 3600); // empty value and old timestamp
  						setcookie("uid",'', time() - 3600 );
						setcookie("smsFlag",'', time() - 3600 );
						setcookie("emailFlag",  '', time() - 3600);
						setcookie("mobile",  '', time() - 3600);
						setcookie("email", '', time() - 3600);
						setcookie("uuid", '', time() - 3600);
						setcookie("logout",1);
						setcookie("facebookReg", 0);
						
  
  
  
//}
session_destroy();
#header( 'Location: '. HOMEPAGE ) ;

?>