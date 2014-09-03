<?php

	
	session_start();

	include("/var/www/conf/config.php");
	include("/var/www/class/LDB2.class.php");
	include("/var/www/class/memberPwd.php");
	include("/var/www/class/sid_encrypt.php");
	
	require_once('log4php/Logger.php');
	Logger::configure('/var/www/properties/log4php.properties');
	$logger = Logger::getLogger("api");

	

	
	
	if (strtolower(trim($_GET['loginFormCaptcha'])) == strtolower($_SESSION['captcha']['code'] )) {
	$db_eimi = new database2($DSN_MYSQL);
					$sql = sprintf("
					SELECT a.ID, a.CUST_ID, a.SMS_VERIFY_FLAG, a.EMAIL_VERIFY_FLAG,MOBILE,EMAIL
					FROM eimi.MEMBER_DATA a inner join MEMBER_FB_DATA b on a.ID = b.ID
					where a.PERSON_ID= '%s' 
					and right(left(from_base64(b.PASSWORD),length(from_base64(b.PASSWORD))-2),length(left(from_base64(b.PASSWORD),length(from_base64(b.PASSWORD))-2))-2) = '%s' ", $db_eimi->esc(trim($_GET['customerID'])),$db_eimi->esc($_GET['loginFormPwd']));
					$db_result = $db_eimi->getRow($sql);
					$db_eimi->cls();
	
		$logger->debug($sql);
	
	
			if (count($db_result)>=1) {
				
				$_SESSION["uid"]=$_GET['customerID'];
				
				$test = new cSidEncrypt();
				$test->cSidEncrypt();
				//$test->sid_encode(strtoupper($_GET['customerID']));

				setcookie("uid", $test->sid_encode(strtoupper(trim($_GET['customerID']))) );
				setcookie("smsFlag", $db_result['SMS_VERIFY_FLAG'] );
				setcookie("emailFlag",  $db_result['EMAIL_VERIFY_FLAG']);
				setcookie("mobile",  $db_result['MOBILE']);
				setcookie("email",  $db_result['EMAIL']);
				setcookie("uuid",  $db_result['ID']);
				
				
				echo '登入成功';
			
			} else {
				
				echo 1;
			
			}
		
	} else {
		$logger->debug('辦識碼錯誤'."\t".$_GET['loginFormCaptcha']."\t".$_SESSION['captcha']['code']."\t".$_GET['customerID']);
		echo 1;
			
	}


?> 